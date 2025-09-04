<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
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
            'departments' => $this->departments,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            // Relationships
            'workspace' => [
                'id' => $this->workspace->id,
                'name' => $this->workspace->name,
            ],
            
            'leader' => [
                'id' => $this->leader->id,
                'first_name' => $this->leader->first_name,
                'last_name' => $this->leader->last_name,
                'email' => $this->leader->email,
            ],
            
            // Load members if available
            'members' => $this->when($this->relationLoaded('members'), function () {
                return $this->members->map(function ($user) {
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
            'members_count' => $this->whenLoaded('members', function () {
                return $this->members->count();
            }),
        ];
    }
}
