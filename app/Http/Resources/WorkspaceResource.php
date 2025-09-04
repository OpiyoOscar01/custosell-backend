<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkspaceResource extends JsonResource
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
            'industry' => $this->industry,
            'employees_count' => $this->employees_count,
            'monthly_budget' => $this->monthly_budget,
            'goals' => $this->goals,
            'features' => $this->features,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Relationships
            'owner' => [
                'id' => $this->owner->id,
                'first_name' => $this->owner->first_name,
                'last_name' => $this->owner->last_name,
                'email' => $this->owner->email,
            ],

            // Load teams if available
            'teams' => TeamResource::collection($this->whenLoaded('teams')),

            // Load members if available
            'members' => $this->when($this->relationLoaded('users'), function () {
                return $this->users->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'email' => $user->email,
                        'role' => $user->pivot->role,
                        'joined_at' => $user->pivot->created_at,
                    ];
                });
            }),

            // Additional computed fields
            'teams_count' => $this->whenLoaded('teams', function () {
                return $this->teams->count();
            }),
        ];
    }
}
