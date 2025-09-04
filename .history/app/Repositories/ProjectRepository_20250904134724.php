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

    public function searchProjects(string $query, int $workspaceId): Collection
    {
        return Project::where('workspace_id', $workspaceId)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('code', 'like', "%{$query}%");
            })
            ->with(['customer', 'manager'])
            ->orderBy('name')
            ->get();
    }

    public function getProjectsByTeam(int $teamId): Collection
    {
        return Project::whereHas('team_members', function ($query) use ($teamId) {
            $query->where('team_id', $teamId);
        })
            ->with(['customer', 'manager', 'tasks'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getProjectStatistics(int $projectId): array
    {
        $project = Project::with(['tasks', 'orders', 'invoices'])->find($projectId);

        if (!$project) {
            return [];
        }

        $totalTasks = $project->tasks->count();
        $completedTasks = $project->tasks->where('status', 'completed')->count();
        $totalOrders = $project->orders->count();
        $totalRevenue = $project->invoices->where('status', 'paid')->sum('total_amount');

        return [
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'completion_percentage' => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0,
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'start_date' => $project->start_date,
            'end_date' => $project->end_date,
            'status' => $project->status
        ];
    }
}
