<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'workspace_id' => 'sometimes|required|exists:workspaces,id',
            'leader_id' => 'sometimes|required|exists:users,id',
            'departments' => 'nullable|array',
            'departments.*' => 'string'
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
            'name.required' => 'Team name is required',
            'name.max' => 'Team name cannot exceed 255 characters',
            'workspace_id.required' => 'Workspace is required',
            'workspace_id.exists' => 'Selected workspace does not exist',
            'leader_id.required' => 'Team leader is required',
            'leader_id.exists' => 'Selected leader does not exist',
            'departments.array' => 'Departments must be provided as an array'
        ];
    }
}
