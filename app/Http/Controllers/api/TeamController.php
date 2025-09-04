<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Models\Team;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of teams
     */
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Team::class);

        $query = Team::with(['workspace', 'leader', 'members']);

        // Filter by workspace if specified
        if ($request->filled('workspace_id')) {
            $workspace = Workspace::findOrFail($request->workspace_id);
            Gate::authorize('view', $workspace);
            $query->where('workspace_id', $request->workspace_id);
        } else {
            // Only show teams from user's workspaces if not admin
            if (!$request->user()->hasRole('admin')) {
                $userWorkspaceIds = $request->user()->workspaces()->pluck('workspaces.id');
                $query->whereIn('workspace_id', $userWorkspaceIds);
            }
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by leader
        if ($request->filled('leader_id')) {
            $query->where('leader_id', $request->leader_id);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $teams = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $teams,
            'message' => 'Teams retrieved successfully'
        ]);
    }

    /**
     * Store a newly created team
     */
    public function store(StoreTeamRequest $request): JsonResponse
    {
        Gate::authorize('create', Team::class);

        // Verify workspace access
        $workspace = Workspace::findOrFail($request->workspace_id);
        Gate::authorize('view', $workspace);

        // Verify leader is in the workspace
        if (!$workspace->users()->where('user_id', $request->leader_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Team leader must be a member of the workspace'
            ], 422);
        }

        $team = Team::create([
            'name' => $request->name,
            'description' => $request->description,
            'workspace_id' => $request->workspace_id,
            'leader_id' => $request->leader_id,
            'departments' => $request->departments ?? []
        ]);

        // Add the leader as a team member with admin role
        $team->members()->attach($request->leader_id, [
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'data' => $team->load(['workspace', 'leader', 'members']),
            'message' => 'Team created successfully'
        ], 201);
    }

    /**
     * Display the specified team
     */
    public function show(Team $team): JsonResponse
    {
        Gate::authorize('view', $team);

        $team->load(['workspace', 'leader', 'members']);

        return response()->json([
            'success' => true,
            'data' => $team,
            'message' => 'Team retrieved successfully'
        ]);
    }

    /**
     * Update the specified team
     */
    public function update(UpdateTeamRequest $request, Team $team): JsonResponse
    {
        Gate::authorize('update', $team);

        // If changing leader, verify they are in the workspace
        if ($request->filled('leader_id') && $request->leader_id !== $team->leader_id) {
            if (!$team->workspace->users()->where('user_id', $request->leader_id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'New team leader must be a member of the workspace'
                ], 422);
            }

            // Add new leader to team if not already a member
            if (!$team->members()->where('user_id', $request->leader_id)->exists()) {
                $team->members()->attach($request->leader_id, [
                    'role' => 'admin',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                // Update their role to admin
                $team->members()->updateExistingPivot($request->leader_id, [
                    'role' => 'admin',
                    'updated_at' => now()
                ]);
            }

            // Update old leader role to member (but keep them in team)
            if ($team->members()->where('user_id', $team->leader_id)->exists()) {
                $team->members()->updateExistingPivot($team->leader_id, [
                    'role' => 'member',
                    'updated_at' => now()
                ]);
            }
        }

        $team->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => $team->load(['workspace', 'leader', 'members']),
            'message' => 'Team updated successfully'
        ]);
    }

    /**
     * Remove the specified team
     */
    public function destroy(Team $team): JsonResponse
    {
        Gate::authorize('delete', $team);

        $team->delete();

        return response()->json([
            'success' => true,
            'message' => 'Team deleted successfully'
        ]);
    }

    /**
     * Add a member to the team
     */
    public function addMember(Request $request, Team $team): JsonResponse
    {
        Gate::authorize('update', $team);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|in:admin,member'
        ]);

        // Verify user is in the workspace
        if (!$team->workspace->users()->where('user_id', $request->user_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'User must be a member of the workspace to join the team'
            ], 422);
        }

        // Check if user is already a team member
        if ($team->members()->where('user_id', $request->user_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'User is already a member of this team'
            ], 422);
        }

        $team->members()->attach($request->user_id, [
            'role' => $request->role,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'data' => $team->load(['members']),
            'message' => 'Member added to team successfully'
        ]);
    }

    /**
     * Update a member's role in the team
     */
    public function updateMember(Request $request, Team $team, User $user): JsonResponse
    {
        Gate::authorize('update', $team);

        $request->validate([
            'role' => 'required|string|in:admin,member'
        ]);

        // Check if user is a team member
        if (!$team->members()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'User is not a member of this team'
            ], 422);
        }

        // Cannot change team leader's role to member (must transfer leadership first)
        if ($team->leader_id === $user->id && $request->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot change team leader role. Transfer leadership first.'
            ], 422);
        }

        $team->members()->updateExistingPivot($user->id, [
            'role' => $request->role,
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'data' => $team->load(['members']),
            'message' => 'Member role updated successfully'
        ]);
    }

    /**
     * Remove a member from the team
     */
    public function removeMember(Team $team, User $user): JsonResponse
    {
        Gate::authorize('update', $team);

        // Cannot remove team leader
        if ($team->leader_id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot remove team leader. Transfer leadership first.'
            ], 422);
        }

        // Check if user is a team member
        if (!$team->members()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'User is not a member of this team'
            ], 422);
        }

        $team->members()->detach($user->id);

        return response()->json([
            'success' => true,
            'message' => 'Member removed from team successfully'
        ]);
    }

    /**
     * Transfer team leadership
     */
    public function transferLeadership(Request $request, Team $team): JsonResponse
    {
        Gate::authorize('update', $team);

        $request->validate([
            'new_leader_id' => 'required|exists:users,id'
        ]);

        // Verify new leader is in the workspace
        if (!$team->workspace->users()->where('user_id', $request->new_leader_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'New leader must be a member of the workspace'
            ], 422);
        }

        $oldLeaderId = $team->leader_id;

        // Add new leader to team if not already a member
        if (!$team->members()->where('user_id', $request->new_leader_id)->exists()) {
            $team->members()->attach($request->new_leader_id, [
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            // Update their role to admin
            $team->members()->updateExistingPivot($request->new_leader_id, [
                'role' => 'admin',
                'updated_at' => now()
            ]);
        }

        // Update team leader
        $team->update(['leader_id' => $request->new_leader_id]);

        // Update old leader role to member (but keep them in team)
        if ($team->members()->where('user_id', $oldLeaderId)->exists()) {
            $team->members()->updateExistingPivot($oldLeaderId, [
                'role' => 'member',
                'updated_at' => now()
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $team->load(['workspace', 'leader', 'members']),
            'message' => 'Team leadership transferred successfully'
        ]);
    }

    /**
     * Get team statistics
     */
    public function statistics(Team $team): JsonResponse
    {
        Gate::authorize('view', $team);

        $stats = [
            'total_members' => $team->members()->count(),
            'member_roles' => $team->members()
                ->select('role')
                ->groupBy('role')
                ->selectRaw('role, count(*) as count')
                ->pluck('count', 'role')
                ->toArray(),
            'departments' => $team->departments,
            'active_projects' => $team->projects()->where('status', '!=', 'completed')->count() ?? 0,
            'active_tasks' => $team->tasks()->where('status', '!=', 'completed')->count() ?? 0
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Team statistics retrieved successfully'
        ]);
    }
}