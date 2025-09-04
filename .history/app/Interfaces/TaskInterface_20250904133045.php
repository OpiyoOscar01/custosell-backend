<?php

namespace App\Interfaces;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskInterface
{
    public function getAllTasks(int $workspaceId): Collection;
    public function getTaskById(int $id): ?Task;
    public function createTask(array $data): Task;
    public function updateTask(int $id, array $data): bool;
    public function deleteTask(int $id): bool;
    public function getTasksByStatus(string $status, int $workspaceId): Collection;
    public function getTasksByPriority(string $priority, int $projectId): Collection;
    public function getTasksByAssignee(int $userId): Collection;
    public function getOverdueTasks(int $projectId): Collection;
    public function searchTasks(string $query, int $workspaceId): Collection;
    public function getTasksByProject(int $projectId): Collection;
    public function getMyTasks(int $userId): Collection;
    public function updateTaskStatus(int $id, string $status): bool;
}