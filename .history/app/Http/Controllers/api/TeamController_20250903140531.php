<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Models\Team;
use App\Models\Workspace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of teams for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        // Get teams where user is leader or member, optionally filtered by workspace
        $query = $user->teams()->with(['workspace', 'leader']);
        
        if ($request->has('workspace_id')) {
            $query->where('workspace_id', $request->workspace_id);
        }
        
        $teams = $query->get()
            ->merge($user->ledTeams()->with(['workspace'])->get());

        return response()->json([
            'success' => true,
            'data' => $teams,
            'message' => 'Teams retrieved successfully'
        ]);
    }

    /**
     * Store a newly created team.
     */
    public function store(StoreTeamRequest $request): JsonResponse
    {
        try {
            // Check if user has access to the workspace
            $workspace = Workspace::findOrFail($request->workspace_id);
            $user = Auth::user();
            
            if (!$workspace->hasMember($user) && $workspace->owner_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have access to this workspace'
                ], 403);
            }

            $team = Team::create($request->validated());
            
            // Add leader as team member with leader role
            $team->members()->attach($request->leader_id, ['role' => 'leader']);

            $team->load(['workspace', 'leader', 'members']);

            return response()->json([
                'success' => true,
                'data' => $team,
                'message' => 'Team created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create team',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified team.
     */
    public function show(Team $team): JsonResponse
    {
        // Check if user has access to this team's workspace
        $user = Auth::user();
        $workspace = $team->workspace;
        
        if (!$workspace->hasMember($user) && $workspace->owner_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this team'
            ], 403);
        }

        $team->load(['workspace', 'leader', 'members']);

        return response()->json([
            'success' => true,
            'data' => $team,
            'message' => 'Team retrieved successfully'
        ]);
    }

    /**
     * Update the specified team.
     */
    public function update(UpdateTeamRequest $request, Team $team): JsonResponse
    {
        // Check if user is team leader, workspace owner, or workspace admin
        $user = Auth::user();
        $workspace = $team->workspace;
        $userRole = $workspace->getUserRole($user);
        
        if ($team->leader_id !== $user->id && 
            $workspace->owner_id !== $user->id && 
            $userRole !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to update this team'
            ], 403);
        }

        try {
            $team->update($request->validated());
            $team->load(['workspace', 'leader', 'members']);

            return response()->json([
                'success' => true,
                'data' => $team,
                'message' => 'Team updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update team',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified team.
     */
    public function destroy(Team $team): JsonResponse
    {
        // Check if user is team leader, workspace owner, or workspace admin
        $user = Auth::user();
        $workspace = $team->workspace;
        $userRole = $workspace->getUserRole($user);
        
        if ($team->leader_id !== $user->id && 
            $workspace->owner_id !== $user->id && 
            $userRole !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete this team'
            ], 403);
        }

        try {
            $team->delete();

            return response()->json([
                'success' => true,
                'message' => 'Team deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete team',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add a member to the team
     */
    public function addMember(Request $request, Team $team): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:member,leader'
        ]);

        // Check if user is team leader, workspace owner, or workspace admin
        $user = Auth::user();
        $workspace = $team->workspace;
        $userRole = $workspace->getUserRole($user);
        
        if ($team->leader_id !== $user->id && 
            $workspace->owner_id !== $user->id && 
            $userRole !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to add members'
            ], 403);
        }

        try {
            if ($team->hasMember($user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is already a member of this team'
                ], 400);
            }

            $team->members()->attach($request->user_id, ['role' => $request->role]);

            return response()->json([
                'success' => true,
                'message' => 'Member added successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add member',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove a member from the team
     */
    public function removeMember(Request $request, Team $team): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        // Check if user is team leader, workspace owner, or workspace admin
        $user = Auth::user();
        $workspace = $team->workspace;
        $userRole = $workspace->getUserRole($user);
        
        if ($team->leader_id !== $user->id && 
            $workspace->owner_id !== $user->id && 
            $userRole !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to remove members'
            ], 403);
        }

        // Cannot remove team leader
        if ($request->user_id == $team->leader_id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot remove team leader'
            ], 400);
        }

        try {
            $team->members()->detach($request->user_id);

            return response()->json([
                'success' => true,
                'message' => 'Member removed successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove member',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
