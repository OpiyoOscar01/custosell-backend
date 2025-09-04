<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'customer_id' => 'required|exists:customers,id',
            'manager_id' => 'required|exists:users,id',
            'team_id' => 'nullable|exists:teams,id',
            'status' => 'required|in:planning,active,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'budget' => 'nullable|numeric|min:0',
            'estimated_hours' => 'nullable|integer|min:0',
            'progress' => 'nullable|integer|min:0|max:100',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240', // 10MB max
            'notes' => 'nullable|string|max:2000',
            'is_billable' => 'nullable|boolean',
            'hourly_rate' => 'nullable|numeric|min:0',
            'workspace_id' => 'required|integer|exists:workspaces,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Project name is required.',
            'customer_id.required' => 'Customer is required.',
            'customer_id.exists' => 'Selected customer does not exist.',
            'team_id.exists' => 'Selected team does not exist.',
            'status.required' => 'Project status is required.',
            'status.in' => 'Invalid project status.',
            'priority.required' => 'Project priority is required.',
            'priority.in' => 'Invalid project priority.',
            'start_date.required' => 'Start date is required.',
            'start_date.after_or_equal' => 'Start date must be today or later.',
            'end_date.after' => 'End date must be after start date.',
            'budget.min' => 'Budget must be a positive number.',
            'estimated_hours.min' => 'Estimated hours must be a positive number.',
            'progress.min' => 'Progress must be between 0 and 100.',
            'progress.max' => 'Progress must be between 0 and 100.',
            'attachments.*.file' => 'Each attachment must be a valid file.',
            'attachments.*.max' => 'Each attachment must not exceed 10MB.',
            'hourly_rate.min' => 'Hourly rate must be a positive number.',
            'workspace_id.required' => 'Workspace is required.',
            'workspace_id.exists' => 'Selected workspace does not exist.',
        ];
    }
}
