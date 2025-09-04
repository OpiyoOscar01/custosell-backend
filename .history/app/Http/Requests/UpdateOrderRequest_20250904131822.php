<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
        $orderId = $this->route('order');
        
        return [
            'customer_id' => 'sometimes|required|exists:customers,id',
            'order_number' => 'nullable|string|max:100|unique:orders,order_number,' . $orderId,
            'status' => 'sometimes|required|in:draft,pending,confirmed,processing,shipped,delivered,cancelled,refunded',
            'order_date' => 'sometimes|required|date',
            'delivery_date' => 'nullable|date|after_or_equal:order_date',
            'subtotal' => 'sometimes|required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'shipping_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'sometimes|required|numeric|min:0',
            'currency' => 'sometimes|required|string|size:3',
            'payment_method' => 'nullable|in:cash,card,bank_transfer,cheque,mobile_money',
            'payment_status' => 'sometimes|required|in:pending,partial,paid,failed,refunded',
            'shipping_address' => 'nullable|array',
            'shipping_address.street' => 'required_with:shipping_address|string|max:255',
            'shipping_address.city' => 'required_with:shipping_address|string|max:100',
            'shipping_address.state' => 'nullable|string|max:100',
            'shipping_address.postal_code' => 'nullable|string|max:20',
            'shipping_address.country' => 'required_with:shipping_address|string|max:100',
            'billing_address' => 'nullable|array',
            'billing_address.street' => 'required_with:billing_address|string|max:255',
            'billing_address.city' => 'required_with:billing_address|string|max:100',
            'billing_address.state' => 'nullable|string|max:100',
            'billing_address.postal_code' => 'nullable|string|max:20',
            'billing_address.country' => 'required_with:billing_address|string|max:100',
            'notes' => 'nullable|string|max:1000',
            'items' => 'nullable|array',
            'items.*.product_id' => 'required_with:items|exists:products,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
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
            'order_number.unique' => 'This order number already exists.',
            'status.required' => 'Order status is required.',
            'status.in' => 'Invalid order status.',
            'order_date.required' => 'Order date is required.',
            'delivery_date.after_or_equal' => 'Delivery date must be on or after order date.',
            'subtotal.required' => 'Subtotal is required.',
            'total_amount.required' => 'Total amount is required.',
            'currency.required' => 'Currency is required.',
            'currency.size' => 'Currency must be a 3-letter code.',
            'payment_status.required' => 'Payment status is required.',
            'payment_status.in' => 'Invalid payment status.',
            'items.*.product_id.required_with' => 'Product is required for each item.',
            'items.*.product_id.exists' => 'One or more selected products do not exist.',
            'items.*.quantity.required_with' => 'Quantity is required for each item.',
            'items.*.quantity.min' => 'Quantity must be at least 1.',
            'items.*.unit_price.required_with' => 'Unit price is required for each item.',
            'items.*.total_amount.required_with' => 'Total amount is required for each item.',
            'workspace_id.required' => 'Workspace is required.',
            'workspace_id.exists' => 'Selected workspace does not exist.',
        ];
    }
}
