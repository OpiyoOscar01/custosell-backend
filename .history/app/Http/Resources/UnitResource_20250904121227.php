<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
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
            'symbol' => $this->symbol,
            'type' => $this->type,
            'is_base_unit' => $this->is_base_unit,
            'conversion_factor' => $this->conversion_factor,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Include relationships when loaded
            'base_unit' => new UnitResource($this->whenLoaded('baseUnit')),
            'child_units' => UnitResource::collection($this->whenLoaded('childUnits')),
            'workspace' => new WorkspaceResource($this->whenLoaded('workspace')),

            // Statistics when available
            'child_units_count' => $this->whenCounted('childUnits'),
        ];
    }
}
