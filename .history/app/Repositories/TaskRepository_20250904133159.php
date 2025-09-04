<?php

namespace App\Repositories;

use App\Models\Task;
use App\Interfaces\TaskInterface;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository implements TaskInterface
{
    public function getAllTasks(int $workspaceId): Collection
    {
        return Task::whereHas('project', function ($query) use ($workspaceId) {
                $query->where('workspace_id', $workspaceId);
            })
            ->with(['project', 'assignedUser', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getTaskById(int $id): ?Task
    {
        return Task::with(['project', 'assignedUser', 'creator', 'timeEntries'])
            ->find($id);
    }

    public function createTask(array $data): Task
    {
        return Task::create($data);
    }

    public function updateTask(int $id, array $data): bool
    {
        return Task::where('id', $id)->update($data);
    }

    public function deleteTask(int $id): bool
    {
        return Task::destroy($id);
    }

    public function getTasksByStatus(string $status, int $workspaceId): Collection
    {
        return Task::whereHas('project', function ($query) use ($workspaceId) {
                $query->where('workspace_id', $workspaceId);
            })
            ->where('status', $status)
            ->with(['assignedUser', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getTasksByPriority(string $priority, int $projectId): Collection
    {
        return Task::where('project_id', $projectId)
            ->where('priority', $priority)
            ->with(['assignedUser', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getTasksByAssignee(int $userId): Collection
    {
        return Task::where('assigned_to', $userId)
            ->with(['project', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getOverdueTasks(int $projectId): Collection
    {
        return Task::where('project_id', $projectId)
            ->where('due_date', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with(['assignedUser', 'creator'])
            ->orderBy('due_date')
            ->get();
    }

    public function searchTasks(string $query, int $workspaceId): Collection
    {
        return Task::whereHas('project', function ($q) use ($workspaceId) {
                $q->where('workspace_id', $workspaceId);
            })
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->with(['project', 'assignedUser', 'creator'])
            ->orderBy('title')
            ->get();
    }

    public function getTasksByProject(int $projectId): Collection
    {
        return Task::where('project_id', $projectId)
            ->with(['assignedUser', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getMyTasks(int $userId): Collection
    {
        return Task::where('assigned_to', $userId)
            ->with(['project', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function updateTaskStatus(int $id, string $status): bool
    {
        return Task::where('id', $id)->update(['status' => $status]);
    }
}
