<?php

namespace App\Services;

use Exception;
use App\Models\Project;
use App\Interfaces\ProjectInterface;
use Illuminate\Database\Eloquent\Collection;

class ProjectService
{
    protected $projectRepository;

    public function __construct(ProjectInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function getAllProjects(int $workspaceId): Collection
    {
        return $this->projectRepository->getAllProjects($workspaceId);
    }

    public function getProjectById(int $id): ?Project
    {
        return $this->projectRepository->getProjectById($id);
    }

    public function createProject(array $data): Project
    {
        // Generate project code if not provided
        if (!isset($data['code'])) {
            $data['code'] = $this->generateProjectCode($data['workspace_id']);
        }

        return $this->projectRepository->createProject($data);
    }

    public function updateProject(int $id, array $data): bool
    {
        return $this->projectRepository->updateProject($id, $data);
    }

    public function deleteProject(int $id): bool
    {
        $project = $this->getProjectById($id);
        if (!$project) {
            return false;
        }

        // Check if project has tasks
        if ($project->tasks()->count() > 0) {
            throw new Exception('Cannot delete project with existing tasks');
        }

        return $this->projectRepository->deleteProject($id);
    }

    public function getProjectsByStatus(string $status, int $workspaceId): Collection
    {
        return $this->projectRepository->getProjectsByStatus($status, $workspaceId);
    }

    public function getProjectsByManager(int $managerId): Collection
    {
        return $this->projectRepository->getProjectsByManager($managerId);
    }

    public function getProjectsByCustomer(int $customerId): Collection
    {
        return $this->projectRepository->getProjectsByCustomer($customerId);
    }

    public function getActiveProjects(int $workspaceId): Collection
    {
        return $this->projectRepository->getActiveProjects($workspaceId);
    }

    public function searchProjects(string $query, int $workspaceId): Collection
    {
        return $this->projectRepository->searchProjects($query, $workspaceId);
    }

    public function getProjectsByTeam(int $teamId): Collection
    {
        return $this->projectRepository->getProjectsByTeam($teamId);
    }

    public function getProjectStatistics(int $projectId): array
    {
        return $this->projectRepository->getProjectStatistics($projectId);
    }

    private function generateProjectCode(int $workspaceId): string
    {
        $lastProject = Project::where('workspace_id', $workspaceId)
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastProject ? (int)substr($lastProject->code, -4) + 1 : 1;
        return 'PROJ' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
