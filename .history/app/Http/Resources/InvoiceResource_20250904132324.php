<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
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
            'invoice_number' => $this->invoice_number,
            'status' => $this->status,
            'issue_date' => $this->issue_date?->toDateString(),
            'due_date' => $this->due_date?->toDateString(),
            'subtotal' => $this->subtotal,
            'tax_amount' => $this->tax_amount,
            'discount_amount' => $this->discount_amount,
            'total_amount' => $this->total_amount,
            'currency' => $this->currency,
            'payment_terms' => $this->payment_terms,
            'notes' => $this->notes,
            'footer_text' => $this->footer_text,
            'billing_address' => $this->billing_address,
            'items' => $this->items,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            
            // Relationships
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'order' => new OrderResource($this->whenLoaded('order')),
            'project' => new ProjectResource($this->whenLoaded('project')),
            'workspace' => new WorkspaceResource($this->whenLoaded('workspace')),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
            
            // Computed fields
            'days_until_due' => $this->when(
                $this->due_date,
                fn() => max(0, now()->diffInDays($this->due_date, false))
            ),
            'is_overdue' => $this->when(
                $this->due_date,
                fn() => $this->due_date->isPast() && $this->status !== 'paid'
            ),
            'paid_amount' => $this->when(
                $this->relationLoaded('payments'),
                fn() => $this->payments->where('status', 'completed')->sum('amount')
            ),
            'balance_due' => $this->when(
                $this->relationLoaded('payments'),
                fn() => $this->total_amount - $this->payments->where('status', 'completed')->sum('amount')
            ),
            'items_count' => is_array($this->items) ? count($this->items) : 0,
        ];
    }
}
