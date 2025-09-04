<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkspaceRequest;
use App\Http\Requests\UpdateWorkspaceRequest;
use App\Http\Resources\WorkspaceResource;
use App\Models\Workspace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of workspaces for the authenticated user.
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();
        
        // Get workspaces where user is owner or member
        $workspaces = $user->workspaces()
            ->with(['owner', 'teams'])
            ->get()
            ->merge($user->ownedWorkspaces()->with(['teams'])->get());

        return response()->json([
            'success' => true,
            'data' => WorkspaceResource::collection($workspaces),
            'message' => 'Workspaces retrieved successfully'
        ]);
    }

    /**
     * Store a newly created workspace.
     */
    public function store(StoreWorkspaceRequest $request): JsonResponse
    {
        try {
            $workspace = Workspace::create([
                ...$request->validated(),
                'owner_id' => Auth::id()
            ]);

            // Add owner as admin member
            $workspace->users()->attach(Auth::id(), ['role' => 'owner']);

            $workspace->load(['owner', 'teams']);

            return response()->json([
                'success' => true,
                'data' => new WorkspaceResource($workspace),
                'message' => 'Workspace created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create workspace',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified workspace.
     */
    public function show(Workspace $workspace): JsonResponse
    {
        // Check if user has access to this workspace
        $user = Auth::user();
        if (!$workspace->hasMember($user) && $workspace->owner_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this workspace'
            ], 403);
        }

        $workspace->load(['owner', 'teams.leader', 'users']);

        return response()->json([
            'success' => true,
            'data' => new WorkspaceResource($workspace),
            'message' => 'Workspace retrieved successfully'
        ]);
    }

    /**
     * Update the specified workspace.
     */
    public function update(UpdateWorkspaceRequest $request, Workspace $workspace): JsonResponse
    {
        // Check if user is owner or admin
        $user = Auth::user();
        $userRole = $workspace->getUserRole($user);
        
        if ($workspace->owner_id !== $user->id && $userRole !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to update this workspace'
            ], 403);
        }

        try {
            $workspace->update($request->validated());
            $workspace->load(['owner', 'teams']);

            return response()->json([
                'success' => true,
                'data' => new WorkspaceResource($workspace),
                'message' => 'Workspace updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update workspace',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified workspace.
     */
    public function destroy(Workspace $workspace): JsonResponse
    {
        // Only owner can delete workspace
        if ($workspace->owner_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Only the workspace owner can delete this workspace'
            ], 403);
        }

        try {
            $workspace->delete();

            return response()->json([
                'success' => true,
                'message' => 'Workspace deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete workspace',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add a member to the workspace
     */
    public function addMember(Request $request, Workspace $workspace): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:member,admin'
        ]);

        // Check if user is owner or admin
        $user = Auth::user();
        $userRole = $workspace->getUserRole($user);
        
        if ($workspace->owner_id !== $user->id && $userRole !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to add members'
            ], 403);
        }

        try {
            if ($workspace->hasMember(Auth::user())) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is already a member of this workspace'
                ], 400);
            }

            $workspace->users()->attach($request->user_id, ['role' => $request->role]);

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
     * Remove a member from the workspace
     */
    public function removeMember(Request $request, Workspace $workspace): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        // Check if user is owner or admin
        $user = Auth::user();
        $userRole = $workspace->getUserRole($user);
        
        if ($workspace->owner_id !== $user->id && $userRole !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to remove members'
            ], 403);
        }

        // Cannot remove owner
        if ($request->user_id == $workspace->owner_id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot remove workspace owner'
            ], 400);
        }

        try {
            $workspace->users()->detach($request->user_id);

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
