<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkspaceRequest;
use App\Http\Requests\UpdateWorkspaceRequest;
use App\Models\Workspace;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class WorkspaceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of workspaces
     */
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Workspace::class);

        $query = Workspace::with(['owner', 'users']);

        // Filter by user's workspaces if not admin
        if (!$request->user()->hasRole('admin')) {
            $query->whereHas('users', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            })->orWhere('owner_id', $request->user()->id);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('industry', 'like', "%{$search}%");
            });
        }

        // Filter by industry
        if ($request->filled('industry')) {
            $query->where('industry', $request->industry);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $workspaces = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $workspaces,
            'message' => 'Workspaces retrieved successfully'
        ]);
    }

    /**
     * Store a newly created workspace
     */
    public function store(StoreWorkspaceRequest $request): JsonResponse
    {
        Gate::authorize('create', Workspace::class);

        $workspace = Workspace::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => $request->user()->id,
            'industry' => $request->industry,
            'employees_count' => $request->employees_count,
            'monthly_budget' => $request->monthly_budget,
            'goals' => $request->goals ?? [],
            'features' => $request->features ?? []
        ]);

        // Add the owner as a member with admin role
        $workspace->users()->attach($request->user()->id, [
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'data' => $workspace->load(['owner', 'users']),
            'message' => 'Workspace created successfully'
        ], 201);
    }

    /**
     * Display the specified workspace
     */
    public function show(Workspace $workspace): JsonResponse
    {
        Gate::authorize('view', $workspace);

        $workspace->load(['owner', 'users', 'teams.members']);

        return response()->json([
            'success' => true,
            'data' => $workspace,
            'message' => 'Workspace retrieved successfully'
        ]);
    }

    /**
     * Update the specified workspace
     */
    public function update(UpdateWorkspaceRequest $request, Workspace $workspace): JsonResponse
    {
        Gate::authorize('update', $workspace);

        $workspace->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => $workspace->load(['owner', 'users']),
            'message' => 'Workspace updated successfully'
        ]);
    }

    /**
     * Remove the specified workspace
     */
    public function destroy(Workspace $workspace): JsonResponse
    {
        Gate::authorize('delete', $workspace);

        $workspace->delete();

        return response()->json([
            'success' => true,
            'message' => 'Workspace deleted successfully'
        ]);
    }

    /**
     * Add a user to the workspace
     */
    public function addMember(Request $request, Workspace $workspace): JsonResponse
    {
        Gate::authorize('update', $workspace);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|in:admin,member,viewer'
        ]);

        // Check if user is already a member
        if ($workspace->users()->where('user_id', $request->user_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'User is already a member of this workspace'
            ], 422);
        }

        $workspace->users()->attach($request->user_id, [
            'role' => $request->role,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'data' => $workspace->load(['users']),
            'message' => 'Member added to workspace successfully'
        ]);
    }

    /**
     * Update a member's role in the workspace
     */
    public function updateMember(Request $request, Workspace $workspace, User $user): JsonResponse
    {
        Gate::authorize('update', $workspace);

        $request->validate([
            'role' => 'required|string|in:admin,member,viewer'
        ]);

        // Check if user is a member
        if (!$workspace->users()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'User is not a member of this workspace'
            ], 422);
        }

        // Cannot change owner's role
        if ($workspace->owner_id === $user->id && $request->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot change workspace owner role'
            ], 422);
        }

        $workspace->users()->updateExistingPivot($user->id, [
            'role' => $request->role,
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'data' => $workspace->load(['users']),
            'message' => 'Member role updated successfully'
        ]);
    }

    /**
     * Remove a member from the workspace
     */
    public function removeMember(Workspace $workspace, User $user): JsonResponse
    {
        Gate::authorize('update', $workspace);

        // Cannot remove workspace owner
        if ($workspace->owner_id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot remove workspace owner'
            ], 422);
        }

        // Check if user is a member
        if (!$workspace->users()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'User is not a member of this workspace'
            ], 422);
        }

        $workspace->users()->detach($user->id);

        return response()->json([
            'success' => true,
            'message' => 'Member removed from workspace successfully'
        ]);
    }

    /**
     * Get workspace statistics
     */
    public function statistics(Workspace $workspace): JsonResponse
    {
        Gate::authorize('view', $workspace);

        $stats = [
            'total_members' => $workspace->users()->count(),
            'total_teams' => $workspace->teams()->count(),
            'total_projects' => $workspace->projects()->count() ?? 0,
            'active_tasks' => $workspace->tasks()->where('status', '!=', 'completed')->count() ?? 0,
            'monthly_budget' => $workspace->monthly_budget,
            'member_roles' => $workspace->users()
                ->select('role')
                ->groupBy('role')
                ->selectRaw('role, count(*) as count')
                ->pluck('count', 'role')
                ->toArray()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Workspace statistics retrieved successfully'
        ]);
    }

    /**
     * Get active workspaces
     */
    public function active(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Workspace::class);

        $query = Workspace::with(['owner', 'users']);

        // Filter by user's workspaces if not admin
        if (!$request->user()->hasRole('admin')) {
            $query->whereHas('users', function ($q) use ($request) {
                $q->where('user_id', $request->user()->id);
            })->orWhere('owner_id', $request->user()->id);
        }

        $workspaces = $query->get();

        return response()->json([
            'success' => true,
            'data' => $workspaces,
            'message' => 'Active workspaces retrieved successfully'
        ]);
    }

    /**
     * Get current user's workspace
     */
    public function current(Request $request): JsonResponse
    {
        $user = $request->user();
        $workspace = $user->workspaces()->first();

        if (!$workspace) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'No workspace found for current user'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $workspace,
            'message' => 'Current workspace retrieved successfully'
        ]);
    }
}
