<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'customer_code' => $this->customer_code,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'website' => $this->website,
            'type' => $this->type,
            'tax_number' => $this->tax_number,
            'billing_address' => $this->billing_address,
            'shipping_address' => $this->shipping_address,
            'payment_terms' => $this->payment_terms,
            'credit_limit' => $this->credit_limit,
            'currency' => $this->currency,
            'notes' => $this->notes,
            'is_active' => $this->is_active,
            'workspace_id' => $this->workspace_id,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            
            // Computed fields
            'display_name' => $this->company ?: $this->name,
            'full_address' => $this->getFullAddress(),
            
            // Relationships
            'workspace' => $this->whenLoaded('workspace', function () {
                return new WorkspaceResource($this->workspace);
            }),
            
            // Counts
            'projects_count' => $this->when(
                $this->relationLoaded('projects'),
                $this->projects->count()
            ),
            'orders_count' => $this->when(
                $this->relationLoaded('orders'),
                $this->orders->count()
            ),
            'invoices_count' => $this->when(
                $this->relationLoaded('invoices'),
                $this->invoices->count()
            ),
            'total_invoiced' => $this->when(
                $this->relationLoaded('invoices'),
                $this->invoices->sum('total_amount')
            ),
            'total_paid' => $this->when(
                $this->relationLoaded('payments'),
                $this->payments->sum('amount')
            ),
        ];
    }

    /**
     * Get formatted full address
     */
    private function getFullAddress(): ?string
    {
        $address = $this->billing_address;
        if (!$address) {
            return null;
        }

        $parts = array_filter([
            $address['street'] ?? null,
            $address['city'] ?? null,
            $address['state'] ?? null,
            $address['postal_code'] ?? null,
            $address['country'] ?? null,
        ]);

        return implode(', ', $parts) ?: null;
    }
}
