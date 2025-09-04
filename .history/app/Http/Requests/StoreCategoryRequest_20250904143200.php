<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
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
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7', // For hex colors
            'icon' => 'nullable|string|max:255',
            'parent_id' => 'nullable|integer|exists:categories,id',
            'type' => 'required|string|in:product,expense,income',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'workspace_id' => 'nullable|integer|exists:workspaces,id'
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Category name is required',
            'name.max' => 'Category name cannot exceed 255 characters',
            'type.required' => 'Category type is required',
            'type.in' => 'Category type must be product, expense, or income',
            'workspace_id.exists' => 'Selected workspace does not exist',
            'parent_id.exists' => 'Selected parent category does not exist'
        ];
    }
}
