<?php

namespace App\Interfaces;

use App\Models\TimeEntry;
use Illuminate\Database\Eloquent\Collection;

interface TimeEntryInterface
{
    public function getAllTimeEntries(int $workspaceId): Collection;
    public function getTimeEntryById(int $id): ?TimeEntry;
    public function createTimeEntry(array $data): TimeEntry;
    public function updateTimeEntry(int $id, array $data): bool;
    public function deleteTimeEntry(int $id): bool;
    public function getTimeEntriesByProject(int $projectId): Collection;
    public function getTimeEntriesByTask(int $taskId): Collection;
    public function getTimeEntriesByUser(int $userId): Collection;
    public function getTimeEntriesByDateRange(string $startDate, string $endDate, int $workspaceId): Collection;
    public function startTimer(array $data): TimeEntry;
    public function stopTimer(int $id): bool;
}
