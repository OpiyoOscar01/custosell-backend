<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'budget' => $this->budget,
            'estimated_hours' => $this->estimated_hours,
            'progress' => $this->progress,
            'tags' => $this->tags,
            'notes' => $this->notes,
            'is_billable' => $this->is_billable,
            'hourly_rate' => $this->hourly_rate,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            
            // Relationships
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'team' => new TeamResource($this->whenLoaded('team')),
            'workspace' => new WorkspaceResource($this->whenLoaded('workspace')),
            // 'tasks' => TaskResource::collection($this->whenLoaded('tasks')), // Avoid circular reference
            
            // Computed fields
            'days_remaining' => $this->when(
                $this->end_date,
                fn() => max(0, now()->diffInDays($this->end_date, false))
            ),
            'is_overdue' => $this->when(
                $this->end_date,
                fn() => $this->end_date->isPast() && $this->status !== 'completed'
            ),
            'completion_percentage' => $this->progress ?? 0,
            'tasks_count' => $this->when(
                $this->relationLoaded('tasks'),
                fn() => $this->tasks->count()
            ),
            'completed_tasks_count' => $this->when(
                $this->relationLoaded('tasks'),
                fn() => $this->tasks->where('status', 'completed')->count()
            ),
        ];
    }
}
