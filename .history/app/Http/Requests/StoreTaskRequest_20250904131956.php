<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'project_id' => 'required|exists:projects,id',
            'assignee_id' => 'nullable|exists:users,id',
            'status' => 'required|in:todo,in_progress,review,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date|after_or_equal:today',
            'estimated_hours' => 'nullable|numeric|min:0',
            'actual_hours' => 'nullable|numeric|min:0',
            'progress' => 'nullable|integer|min:0|max:100',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:5120', // 5MB max
            'dependencies' => 'nullable|array',
            'dependencies.*' => 'integer|exists:tasks,id',
            'notes' => 'nullable|string|max:1000',
            'is_billable' => 'nullable|boolean',
            'hourly_rate' => 'nullable|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Task title is required.',
            'project_id.required' => 'Project is required.',
            'project_id.exists' => 'Selected project does not exist.',
            'assignee_id.exists' => 'Selected assignee does not exist.',
            'status.required' => 'Task status is required.',
            'status.in' => 'Invalid task status.',
            'priority.required' => 'Task priority is required.',
            'priority.in' => 'Invalid task priority.',
            'due_date.after_or_equal' => 'Due date must be today or later.',
            'estimated_hours.min' => 'Estimated hours must be a positive number.',
            'actual_hours.min' => 'Actual hours must be a positive number.',
            'progress.min' => 'Progress must be between 0 and 100.',
            'progress.max' => 'Progress must be between 0 and 100.',
            'attachments.*.file' => 'Each attachment must be a valid file.',
            'attachments.*.max' => 'Each attachment must not exceed 5MB.',
            'dependencies.*.exists' => 'One or more selected dependencies do not exist.',
            'hourly_rate.min' => 'Hourly rate must be a positive number.',
        ];
    }
}
