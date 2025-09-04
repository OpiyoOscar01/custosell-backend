<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ProjectResource;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

class ProjectController extends BaseApiController
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        parent::__construct();
        $this->projectService = $projectService;

        // Apply permission middleware
        $this->middleware('permission:view-projects')->only(['index', 'show']);
        $this->middleware('permission:create-projects')->only(['store']);
        $this->middleware('permission:update-projects')->only(['update']);
        $this->middleware('permission:delete-projects')->only(['destroy']);
    }

    /**
     * Display a listing of projects.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $this->authorize('viewAny', Project::class);

            $user = $request->user();
            $workspaceId = $request->get('workspace_id') ?? $user->workspaces()->first()?->id ?? 1;
            $status = $request->get('status');
            $teamId = $request->get('team_id');
            $search = $request->get('search');

            if ($search) {
                $projects = $this->projectService->searchProjects($search, $workspaceId);
            } elseif ($status) {
                $projects = $this->projectService->getProjectsByStatus($status, $workspaceId);
            } elseif ($teamId) {
                $projects = $this->projectService->getProjectsByTeam($teamId);
            } else {
                $projects = $this->projectService->getAllProjects($workspaceId);
            }

            return $this->successResponse(
                ProjectResource::collection($projects),
                'Projects retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Store a newly created project.
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        try {
            $this->authorize('create', Project::class);

            $project = $this->projectService->createProject($request->validated());

            return $this->successResponse(
                new ProjectResource($project),
                'Project created successfully',
                201
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Display the specified project.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $project = $this->projectService->getProjectById($id);

            if (!$project) {
                return $this->errorResponse('Project not found', 404);
            }

            $this->authorize('view', $project);

            return $this->successResponse(
                new ProjectResource($project),
                'Project retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Update the specified project.
     */
    public function update(UpdateProjectRequest $request, int $id): JsonResponse
    {
        try {
            $project = $this->projectService->getProjectById($id);

            if (!$project) {
                return $this->errorResponse('Project not found', 404);
            }

            $this->authorize('update', $project);

            $updated = $this->projectService->updateProject($id, $request->validated());

            if (!$updated) {
                return $this->errorResponse('Failed to update project', 500);
            }

            $project = $this->projectService->getProjectById($id);

            return $this->successResponse(
                new ProjectResource($project),
                'Project updated successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified project.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $project = $this->projectService->getProjectById($id);

            if (!$project) {
                return $this->errorResponse('Project not found', 404);
            }

            $this->authorize('delete', $project);

            $deleted = $this->projectService->deleteProject($id);

            if (!$deleted) {
                return $this->errorResponse('Failed to delete project', 500);
            }

            return $this->successResponse(
                null,
                'Project deleted successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Get active projects.
     */
    public function active(Request $request): JsonResponse
    {
        try {
            $this->authorize('viewAny', Project::class);

            $user = $request->user();
            $workspaceId = $user->workspaces()->first()?->id ?? 1;
            $projects = $this->projectService->getActiveProjects($workspaceId);

            return $this->successResponse(
                ProjectResource::collection($projects),
                'Active projects retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Get project statistics.
     */
    public function statistics(int $id): JsonResponse
    {
        try {
            $project = $this->projectService->getProjectById($id);

            if (!$project) {
                return $this->errorResponse('Project not found', 404);
            }

            $this->authorize('view', $project);

            $stats = $this->projectService->getProjectStatistics($id);

            return $this->successResponse(
                $stats,
                'Project statistics retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Get projects by status.
     */
    public function byStatus(Request $request, string $status): JsonResponse
    {
        try {
            $this->authorize('viewAny', Project::class);

            $user = $request->user();
            $workspaceId = $user->workspaces()->first()?->id ?? 1;
            $projects = $this->projectService->getProjectsByStatus($status, $workspaceId);

            return $this->successResponse(
                ProjectResource::collection($projects),
                'Projects by status retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
