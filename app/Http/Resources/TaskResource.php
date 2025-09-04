<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'due_date' => $this->due_date?->toDateString(),
            'estimated_hours' => $this->estimated_hours,
            'actual_hours' => $this->actual_hours,
            'progress' => $this->progress,
            'tags' => $this->tags,
            'notes' => $this->notes,
            'is_billable' => $this->is_billable,
            'hourly_rate' => $this->hourly_rate,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),

            // Relationships
            'project' => $this->when($this->project_id, fn() => ['id' => $this->project_id, 'name' => $this->project?->name]),
            'assignee' => $this->when(
                $this->relationLoaded('assignee'),
                fn() => [
                    'id' => $this->assignee->id,
                    'name' => $this->assignee->name,
                    'email' => $this->assignee->email,
                ]
            ),
            // 'dependencies' => TaskResource::collection($this->whenLoaded('dependencies')), // Avoid circular reference

            // Computed fields
            'days_until_due' => $this->when(
                $this->due_date,
                fn() => max(0, now()->diffInDays($this->due_date, false))
            ),
            'is_overdue' => $this->when(
                $this->due_date,
                fn() => $this->due_date->isPast() && $this->status !== 'completed'
            ),
            'completion_percentage' => $this->progress ?? 0,
            'time_spent_vs_estimated' => $this->when(
                $this->estimated_hours && $this->actual_hours,
                fn() => round(($this->actual_hours / $this->estimated_hours) * 100, 2)
            ),
        ];
    }
}
