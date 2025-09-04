<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'cost_price' => $this->cost_price,
            'selling_price' => $this->selling_price,
            'min_stock_level' => $this->min_stock_level,
            'max_stock_level' => $this->max_stock_level,
            'current_stock' => $this->current_stock,
            'weight' => $this->weight,
            'dimensions' => $this->dimensions,
            'tax_rate' => $this->tax_rate,
            'discount_rate' => $this->discount_rate,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'tags' => $this->tags,
            'meta_data' => $this->meta_data,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            
            // Relationships
            'category' => new CategoryResource($this->whenLoaded('category')),
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'unit' => new UnitResource($this->whenLoaded('unit')),
            'workspace' => new WorkspaceResource($this->whenLoaded('workspace')),
            
            // Computed fields
            'profit_margin' => $this->when(
                $this->cost_price && $this->selling_price,
                fn() => round((($this->selling_price - $this->cost_price) / $this->selling_price) * 100, 2)
            ),
            'stock_status' => $this->when(
                $this->min_stock_level,
                fn() => $this->current_stock <= $this->min_stock_level ? 'low' : 'normal'
            ),
        ];
    }
}
