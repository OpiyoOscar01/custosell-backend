<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled in controller
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'order_id' => 'nullable|exists:orders,id',
            'project_id' => 'nullable|exists:projects,id',
            'invoice_number' => 'nullable|string|max:100|unique:invoices,invoice_number',
            'status' => 'required|in:draft,sent,viewed,overdue,paid,cancelled',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'payment_terms' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'footer_text' => 'nullable|string|max:500',
            'billing_address' => 'required|array',
            'billing_address.street' => 'required|string|max:255',
            'billing_address.city' => 'required|string|max:100',
            'billing_address.state' => 'nullable|string|max:100',
            'billing_address.postal_code' => 'nullable|string|max:20',
            'billing_address.country' => 'required|string|max:100',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
            'items.*.tax_amount' => 'nullable|numeric|min:0',
            'items.*.total_amount' => 'required|numeric|min:0',
            'workspace_id' => 'required|uuid|exists:workspaces,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'customer_id.required' => 'Customer is required.',
            'customer_id.exists' => 'Selected customer does not exist.',
            'order_id.exists' => 'Selected order does not exist.',
            'project_id.exists' => 'Selected project does not exist.',
            'invoice_number.unique' => 'This invoice number already exists.',
            'status.required' => 'Invoice status is required.',
            'status.in' => 'Invalid invoice status.',
            'issue_date.required' => 'Issue date is required.',
            'due_date.required' => 'Due date is required.',
            'due_date.after_or_equal' => 'Due date must be on or after issue date.',
            'subtotal.required' => 'Subtotal is required.',
            'total_amount.required' => 'Total amount is required.',
            'currency.required' => 'Currency is required.',
            'currency.size' => 'Currency must be a 3-letter code.',
            'billing_address.required' => 'Billing address is required.',
            'billing_address.street.required' => 'Street address is required.',
            'billing_address.city.required' => 'City is required.',
            'billing_address.country.required' => 'Country is required.',
            'items.required' => 'Invoice items are required.',
            'items.min' => 'At least one item is required.',
            'items.*.description.required' => 'Description is required for each item.',
            'items.*.quantity.required' => 'Quantity is required for each item.',
            'items.*.quantity.min' => 'Quantity must be greater than 0.',
            'items.*.unit_price.required' => 'Unit price is required for each item.',
            'items.*.total_amount.required' => 'Total amount is required for each item.',
            'workspace_id.required' => 'Workspace is required.',
            'workspace_id.exists' => 'Selected workspace does not exist.',
        ];
    }
}
