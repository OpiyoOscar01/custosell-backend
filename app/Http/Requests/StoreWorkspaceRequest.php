<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkspaceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization will be handled in controller/middleware
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
            'description' => 'nullable|string',
            'industry' => 'nullable|string|max:255',
            'employees_count' => 'nullable|integer|min:1',
            'monthly_budget' => 'nullable|numeric|min:0',
            'goals' => 'nullable|array',
            'goals.*' => 'string',
            'features' => 'nullable|array',
            'features.*' => 'string'
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Workspace name is required',
            'name.max' => 'Workspace name cannot exceed 255 characters',
            'employees_count.integer' => 'Employees count must be a valid number',
            'employees_count.min' => 'Employees count must be at least 1',
            'monthly_budget.numeric' => 'Monthly budget must be a valid number',
            'monthly_budget.min' => 'Monthly budget cannot be negative',
            'goals.array' => 'Goals must be provided as an array',
            'features.array' => 'Features must be provided as an array'
        ];
    }
}
