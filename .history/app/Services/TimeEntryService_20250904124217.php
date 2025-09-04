<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\TimeEntry;
use App\Interfaces\TimeEntryInterface;
use Illuminate\Database\Eloquent\Collection;

class TimeEntryService
{
    protected $timeEntryRepository;

    public function __construct(TimeEntryInterface $timeEntryRepository)
    {
        $this->timeEntryRepository = $timeEntryRepository;
    }

    public function getAllTimeEntries(int $workspaceId): Collection
    {
        return $this->timeEntryRepository->getAllTimeEntries($workspaceId);
    }

    public function getTimeEntryById(int $id): ?TimeEntry
    {
        return $this->timeEntryRepository->getTimeEntryById($id);
    }

    public function createTimeEntry(array $data): TimeEntry
    {
        // Calculate duration if start and end times are provided
        if (isset($data['start_time']) && isset($data['end_time'])) {
            $startTime = Carbon::parse($data['start_time']);
            $endTime = Carbon::parse($data['end_time']);
            $data['duration'] = $endTime->diffInMinutes($startTime);
        }

        // Calculate total amount if hourly rate is provided
        if (isset($data['hourly_rate']) && isset($data['duration'])) {
            $hours = $data['duration'] / 60;
            $data['total_amount'] = $hours * $data['hourly_rate'];
        }

        // Set default status if not provided
        if (!isset($data['status'])) {
            $data['status'] = 'logged';
        }

        return $this->timeEntryRepository->createTimeEntry($data);
    }

    public function updateTimeEntry(int $id, array $data): bool
    {
        // Recalculate duration and amount if times change
        if (isset($data['start_time']) || isset($data['end_time'])) {
            $timeEntry = $this->getTimeEntryById($id);
            if ($timeEntry) {
                $startTime = Carbon::parse($data['start_time'] ?? $timeEntry->start_time);
                $endTime = Carbon::parse($data['end_time'] ?? $timeEntry->end_time);
                $data['duration'] = $endTime->diffInMinutes($startTime);

                // Recalculate total amount
                $hourlyRate = $data['hourly_rate'] ?? $timeEntry->hourly_rate;
                if ($hourlyRate) {
                    $hours = $data['duration'] / 60;
                    $data['total_amount'] = $hours * $hourlyRate;
                }
            }
        }

        return $this->timeEntryRepository->updateTimeEntry($id, $data);
    }

    public function deleteTimeEntry(int $id): bool
    {
        $timeEntry = $this->getTimeEntryById($id);
        if (!$timeEntry) {
            return false;
        }

        // Check if time entry is approved or invoiced
        if (in_array($timeEntry->status, ['approved', 'invoiced'])) {
            throw new Exception('Cannot delete approved or invoiced time entry');
        }

        return $this->timeEntryRepository->deleteTimeEntry($id);
    }

    public function getTimeEntriesByProject(int $projectId): Collection
    {
        return $this->timeEntryRepository->getTimeEntriesByProject($projectId);
    }

    public function getTimeEntriesByTask(int $taskId): Collection
    {
        return $this->timeEntryRepository->getTimeEntriesByTask($taskId);
    }

    public function getTimeEntriesByUser(int $userId): Collection
    {
        return $this->timeEntryRepository->getTimeEntriesByUser($userId);
    }

    public function getTimeEntriesByDateRange(string $startDate, string $endDate, int $workspaceId): Collection
    {
        return $this->timeEntryRepository->getTimeEntriesByDateRange($startDate, $endDate, $workspaceId);
    }

    public function startTimer(array $data): TimeEntry
    {
        // Ensure required fields for timer
        $data['is_running'] = true;
        $data['start_time'] = now();
        $data['status'] = 'running';

        return $this->timeEntryRepository->startTimer($data);
    }

    public function stopTimer(int $id): bool
    {
        $timeEntry = $this->getTimeEntryById($id);
        if (!$timeEntry || !$timeEntry->is_running) {
            return false;
        }

        $success = $this->timeEntryRepository->stopTimer($id);

        if ($success) {
            // Update status and calculate amount
            $timeEntry->refresh();
            $updateData = ['status' => 'logged'];

            if ($timeEntry->hourly_rate && $timeEntry->duration) {
                $hours = $timeEntry->duration / 60;
                $updateData['total_amount'] = $hours * $timeEntry->hourly_rate;
            }

            $this->updateTimeEntry($id, $updateData);
        }

        return $success;
    }

    public function approveTimeEntry(int $id, int $approverId): bool
    {
        return $this->updateTimeEntry($id, [
            'status' => 'approved',
            'approved_by' => $approverId,
            'approved_at' => now()
        ]);
    }

    public function rejectTimeEntry(int $id, int $approverId): bool
    {
        return $this->updateTimeEntry($id, [
            'status' => 'rejected',
            'approved_by' => $approverId,
            'approved_at' => now()
        ]);
    }
}