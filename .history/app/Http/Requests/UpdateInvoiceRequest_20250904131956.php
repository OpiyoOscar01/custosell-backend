<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
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
        $invoiceId = $this->route('invoice');

        return [
            'customer_id' => 'sometimes|required|exists:customers,id',
            'order_id' => 'nullable|exists:orders,id',
            'project_id' => 'nullable|exists:projects,id',
            'invoice_number' => 'nullable|string|max:100|unique:invoices,invoice_number,' . $invoiceId,
            'status' => 'sometimes|required|in:draft,sent,viewed,overdue,paid,cancelled',
            'issue_date' => 'sometimes|required|date',
            'due_date' => 'sometimes|required|date|after_or_equal:issue_date',
            'subtotal' => 'sometimes|required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'sometimes|required|numeric|min:0',
            'currency' => 'sometimes|required|string|size:3',
            'payment_terms' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'footer_text' => 'nullable|string|max:500',
            'billing_address' => 'nullable|array',
            'billing_address.street' => 'required_with:billing_address|string|max:255',
            'billing_address.city' => 'required_with:billing_address|string|max:100',
            'billing_address.state' => 'nullable|string|max:100',
            'billing_address.postal_code' => 'nullable|string|max:20',
            'billing_address.country' => 'required_with:billing_address|string|max:100',
            'items' => 'nullable|array',
            'items.*.description' => 'required_with:items|string|max:255',
            'items.*.quantity' => 'required_with:items|numeric|min:0.01',
            'items.*.unit_price' => 'required_with:items|numeric|min:0',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
            'items.*.tax_amount' => 'nullable|numeric|min:0',
            'items.*.total_amount' => 'required_with:items|numeric|min:0',
            'workspace_id' => 'sometimes|required|uuid|exists:workspaces,id',
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
            'billing_address.street.required_with' => 'Street address is required when billing address is provided.',
            'billing_address.city.required_with' => 'City is required when billing address is provided.',
            'billing_address.country.required_with' => 'Country is required when billing address is provided.',
            'items.*.description.required_with' => 'Description is required for each item.',
            'items.*.quantity.required_with' => 'Quantity is required for each item.',
            'items.*.quantity.min' => 'Quantity must be greater than 0.',
            'items.*.unit_price.required_with' => 'Unit price is required for each item.',
            'items.*.total_amount.required_with' => 'Total amount is required for each item.',
            'workspace_id.required' => 'Workspace is required.',
            'workspace_id.exists' => 'Selected workspace does not exist.',
        ];
    }
}
