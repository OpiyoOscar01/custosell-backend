<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'amount' => $this->amount,
            'currency' => $this->currency,
            'payment_method' => $this->payment_method,
            'status' => $this->status,
            'payment_date' => $this->payment_date?->toDateString(),
            'reference_number' => $this->reference_number,
            'notes' => $this->notes,
            'gateway_transaction_id' => $this->gateway_transaction_id,
            'gateway_response' => $this->gateway_response,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),

            // Relationships
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'invoice' => $this->when($this->invoice_id, fn() => ['id' => $this->invoice_id]),
            'order' => $this->when($this->order_id, fn() => ['id' => $this->order_id]),
            'workspace' => new WorkspaceResource($this->whenLoaded('workspace')),

            // Computed fields
            'days_since_payment' => $this->payment_date ? now()->diffInDays($this->payment_date) : null,
            'is_successful' => $this->status === 'completed',
        ];
    }
}
