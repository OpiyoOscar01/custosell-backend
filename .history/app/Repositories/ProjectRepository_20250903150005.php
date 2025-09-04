<?php

namespace App\Repositories;

use App\Models\Project;
use App\Interfaces\ProjectInterface;
use Illuminate\Database\Eloquent\Collection;

class ProjectRepository implements ProjectInterface
{
    public function getAllProjects(int $workspaceId): Collection
    {
        return Project::where('workspace_id', $workspaceId)
            ->with(['customer', 'manager', 'tasks'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getProjectById(int $id): ?Project
    {
        return Project::with(['customer', 'manager', 'tasks', 'timeEntries', 'expenses'])
            ->find($id);
    }

    public function createProject(array $data): Project
    {
        return Project::create($data);
    }

    public function updateProject(int $id, array $data): bool
    {
        return Project::where('id', $id)->update($data);
    }

    public function deleteProject(int $id): bool
    {
        return Project::destroy($id);
    }

    public function getProjectsByStatus(string $status, int $workspaceId): Collection
    {
        return Project::where('workspace_id', $workspaceId)
            ->where('status', $status)
            ->with(['customer', 'manager'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getProjectsByManager(int $managerId): Collection
    {
        return Project::where('manager_id', $managerId)
            ->with(['customer', 'tasks'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getProjectsByCustomer(int $customerId): Collection
    {
        return Project::where('customer_id', $customerId)
            ->with(['manager', 'tasks'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getActiveProjects(int $workspaceId): Collection
    {
        return Project::where('workspace_id', $workspaceId)
            ->whereIn('status', ['active', 'in_progress'])
            ->with(['customer', 'manager'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
