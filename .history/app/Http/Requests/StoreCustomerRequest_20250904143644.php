<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Implement authorization logic as needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'customer_code' => 'nullable|string|max:50|unique:customers,customer_code',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'type' => 'required|string|in:individual,business',
            'tax_number' => 'nullable|string|max:50',
            'billing_address' => 'nullable|array',
            'billing_address.street' => 'nullable|string|max:255',
            'billing_address.city' => 'nullable|string|max:100',
            'billing_address.state' => 'nullable|string|max:100',
            'billing_address.postal_code' => 'nullable|string|max:20',
            'billing_address.country' => 'nullable|string|max:100',
            'shipping_address' => 'nullable|array',
            'shipping_address.street' => 'nullable|string|max:255',
            'shipping_address.city' => 'nullable|string|max:100',
            'shipping_address.state' => 'nullable|string|max:100',
            'shipping_address.postal_code' => 'nullable|string|max:20',
            'shipping_address.country' => 'nullable|string|max:100',
            'payment_terms' => 'nullable|string|max:100',
            'credit_limit' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|size:3',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
            'workspace_id' => 'nullable|integer|exists:workspaces,id'
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Customer name is required',
            'name.max' => 'Customer name cannot exceed 255 characters',
            'email.email' => 'Please provide a valid email address',
            'type.required' => 'Customer type is required',
            'type.in' => 'Customer type must be individual or business',
            'workspace_id.required' => 'Workspace ID is required',
            'workspace_id.exists' => 'Selected workspace does not exist',
            'customer_code.unique' => 'Customer code already exists',
            'credit_limit.numeric' => 'Credit limit must be a number',
            'credit_limit.min' => 'Credit limit cannot be negative',
            'currency.size' => 'Currency code must be exactly 3 characters'
        ];
    }
}
