<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'website' => $this->website,
            'tax_id' => $this->tax_id,
            'logo' => $this->logo,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Include relationships when loaded
            'branches' => BranchResource::collection($this->whenLoaded('branches')),
            'brands' => BrandResource::collection($this->whenLoaded('brands')),
            'workspace' => new WorkspaceResource($this->whenLoaded('workspace')),

            // Statistics when available
            'branches_count' => $this->whenCounted('branches'),
            'brands_count' => $this->whenCounted('brands'),
        ];
    }
}
