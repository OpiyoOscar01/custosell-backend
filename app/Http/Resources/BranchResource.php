<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
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
            'code' => $this->code,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'manager_name' => $this->manager_name,
            'manager_email' => $this->manager_email,
            'manager_phone' => $this->manager_phone,
            'is_warehouse' => $this->is_warehouse,
            'is_pos_enabled' => $this->is_pos_enabled,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Include relationships when loaded
            'company' => new CompanyResource($this->whenLoaded('company')),
            'workspace' => new WorkspaceResource($this->whenLoaded('workspace')),
        ];
    }
}
