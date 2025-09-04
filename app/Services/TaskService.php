<?php

namespace App\Services;

use App\Models\Task;
use App\Interfaces\TaskInterface;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    protected $taskRepository;

    public function __construct(TaskInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getAllTasks(int $projectId): Collection
    {
        return $this->taskRepository->getAllTasks($projectId);
    }

    public function getTaskById(int $id): ?Task
    {
        return $this->taskRepository->getTaskById($id);
    }

    public function createTask(array $data): Task
    {
        return $this->taskRepository->createTask($data);
    }

    public function updateTask(int $id, array $data): bool
    {
        return $this->taskRepository->updateTask($id, $data);
    }

    public function deleteTask(int $id): bool
    {
        return $this->taskRepository->deleteTask($id);
    }

    public function getTasksByStatus(string $status, int $projectId): Collection
    {
        return $this->taskRepository->getTasksByStatus($status, $projectId);
    }

    public function getTasksByPriority(string $priority, int $projectId): Collection
    {
        return $this->taskRepository->getTasksByPriority($priority, $projectId);
    }

    public function getTasksByAssignee(int $userId): Collection
    {
        return $this->taskRepository->getTasksByAssignee($userId);
    }

    public function getOverdueTasks(int $projectId): Collection
    {
        return $this->taskRepository->getOverdueTasks($projectId);
    }
}
