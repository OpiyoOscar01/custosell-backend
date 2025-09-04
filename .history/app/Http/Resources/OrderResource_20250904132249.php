<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_number' => $this->order_number,
            'status' => $this->status,
            'order_date' => $this->order_date?->toDateString(),
            'delivery_date' => $this->delivery_date?->toDateString(),
            'subtotal' => $this->subtotal,
            'tax_amount' => $this->tax_amount,
            'discount_amount' => $this->discount_amount,
            'shipping_amount' => $this->shipping_amount,
            'total_amount' => $this->total_amount,
            'currency' => $this->currency,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'shipping_address' => $this->shipping_address,
            'billing_address' => $this->billing_address,
            'notes' => $this->notes,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            
            // Relationships
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'workspace' => new WorkspaceResource($this->whenLoaded('workspace')),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            
            // Computed fields
            'items_count' => $this->when(
                $this->relationLoaded('items'),
                fn() => $this->items->count()
            ),
            'total_quantity' => $this->when(
                $this->relationLoaded('items'),
                fn() => $this->items->sum('quantity')
            ),
            'days_since_order' => $this->order_date ? now()->diffInDays($this->order_date) : null,
            'delivery_status' => $this->when(
                $this->delivery_date,
                fn() => $this->delivery_date->isPast() ? 'delivered' : 'pending'
            ),
        ];
    }
}
