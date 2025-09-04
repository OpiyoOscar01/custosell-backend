<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\TaskResource;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends BaseApiController
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        parent::__construct();
        $this->taskService = $taskService;

        // Apply permission middleware
        $this->middleware('permission:view-tasks')->only(['index', 'show']);
        $this->middleware('permission:create-tasks')->only(['store']);
        $this->middleware('permission:update-tasks')->only(['update']);
        $this->middleware('permission:delete-tasks')->only(['destroy']);
    }

    /**
     * Display a listing of tasks.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $this->authorize('viewAny', Task::class);

            $workspaceId = Auth::user()->current_workspace_id;
            $status = $request->get('status');
            $projectId = $request->get('project_id');
            $assigneeId = $request->get('assignee_id');
            $search = $request->get('search');

            if ($search) {
                $tasks = $this->taskService->searchTasks($search, $workspaceId);
            } elseif ($status) {
                $tasks = $this->taskService->getTasksByStatus($status, $workspaceId);
            } elseif ($projectId) {
                $tasks = $this->taskService->getTasksByProject($projectId);
            } elseif ($assigneeId) {
                $tasks = $this->taskService->getTasksByAssignee($assigneeId);
            } else {
                $tasks = $this->taskService->getAllTasks($workspaceId);
            }

            return $this->successResponse(
                TaskResource::collection($tasks),
                'Tasks retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Store a newly created task.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        try {
            $this->authorize('create', Task::class);

            $task = $this->taskService->createTask($request->validated());

            return $this->successResponse(
                new TaskResource($task),
                'Task created successfully',
                201
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Display the specified task.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $task = $this->taskService->getTaskById($id);

            if (!$task) {
                return $this->errorResponse('Task not found', 404);
            }

            $this->authorize('view', $task);

            return $this->successResponse(
                new TaskResource($task),
                'Task retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Update the specified task.
     */
    public function update(UpdateTaskRequest $request, int $id): JsonResponse
    {
        try {
            $task = $this->taskService->getTaskById($id);

            if (!$task) {
                return $this->errorResponse('Task not found', 404);
            }

            $this->authorize('update', $task);

            $updated = $this->taskService->updateTask($id, $request->validated());

            if (!$updated) {
                return $this->errorResponse('Failed to update task', 500);
            }

            $task = $this->taskService->getTaskById($id);

            return $this->successResponse(
                new TaskResource($task),
                'Task updated successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified task.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $task = $this->taskService->getTaskById($id);

            if (!$task) {
                return $this->errorResponse('Task not found', 404);
            }

            $this->authorize('delete', $task);

            $deleted = $this->taskService->deleteTask($id);

            if (!$deleted) {
                return $this->errorResponse('Failed to delete task', 500);
            }

            return $this->successResponse(
                null,
                'Task deleted successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Get my tasks.
     */
    public function myTasks(): JsonResponse
    {
        try {
            $this->authorize('viewAny', Task::class);

            $tasks = $this->taskService->getMyTasks();

            return $this->successResponse(
                TaskResource::collection($tasks),
                'My tasks retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Update task status.
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        try {
            $task = $this->taskService->getTaskById($id);

            if (!$task) {
                return $this->errorResponse('Task not found', 404);
            }

            $this->authorize('update', $task);

            $updated = $this->taskService->updateTaskStatus($id, $request->get('status'));

            if (!$updated) {
                return $this->errorResponse('Failed to update task status', 500);
            }

            $task = $this->taskService->getTaskById($id);

            return $this->successResponse(
                new TaskResource($task),
                'Task status updated successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
