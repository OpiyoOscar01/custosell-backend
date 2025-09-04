<?php

namespace App\Repositories;

use App\Models\TimeEntry;
use App\Interfaces\TimeEntryInterface;
use Illuminate\Database\Eloquent\Collection;

class TimeEntryRepository implements TimeEntryInterface
{
    public function getAllTimeEntries(int $workspaceId): Collection
    {
        return TimeEntry::where('workspace_id', $workspaceId)
            ->with(['project', 'task', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getTimeEntryById(int $id): ?TimeEntry
    {
        return TimeEntry::with(['project', 'task', 'user', 'approver'])
            ->find($id);
    }

    public function createTimeEntry(array $data): TimeEntry
    {
        return TimeEntry::create($data);
    }

    public function updateTimeEntry(int $id, array $data): bool
    {
        return TimeEntry::where('id', $id)->update($data);
    }

    public function deleteTimeEntry(int $id): bool
    {
        return TimeEntry::destroy($id);
    }

    public function getTimeEntriesByProject(int $projectId): Collection
    {
        return TimeEntry::where('project_id', $projectId)
            ->with(['task', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getTimeEntriesByTask(int $taskId): Collection
    {
        return TimeEntry::where('task_id', $taskId)
            ->with(['project', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getTimeEntriesByUser(int $userId): Collection
    {
        return TimeEntry::where('user_id', $userId)
            ->with(['project', 'task'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getTimeEntriesByDateRange(string $startDate, string $endDate, int $workspaceId): Collection
    {
        return TimeEntry::where('workspace_id', $workspaceId)
            ->whereBetween('start_time', [$startDate, $endDate])
            ->with(['project', 'task', 'user'])
            ->orderBy('start_time', 'desc')
            ->get();
    }

    public function startTimer(array $data): TimeEntry
    {
        return TimeEntry::create(array_merge($data, [
            'start_time' => now(),
            'is_running' => true
        ]));
    }

    public function stopTimer(int $id): bool
    {
        $timeEntry = TimeEntry::find($id);
        if ($timeEntry && $timeEntry->is_running) {
            $duration = now()->diffInMinutes($timeEntry->start_time);
            return $timeEntry->update([
                'end_time' => now(),
                'duration' => $duration,
                'is_running' => false
            ]);
        }
        return false;
    }
}
