<?php

namespace App\Interfaces;

use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;

interface ProjectInterface
{
    public function getAllProjects(int $workspaceId): Collection;
    public function getProjectById(int $id): ?Project;
    public function createProject(array $data): Project;
    public function updateProject(int $id, array $data): bool;
    public function deleteProject(int $id): bool;
    public function getProjectsByStatus(string $status, int $workspaceId): Collection;
    public function getProjectsByManager(int $managerId): Collection;
    public function getProjectsByCustomer(int $customerId): Collection;
    public function getActiveProjects(int $workspaceId): Collection;
    public function searchProjects(string $query, int $workspaceId): Collection;
    public function getProjectsByTeam(int $teamId): Collection;
    public function getProjectStatistics(int $projectId): array;
}
