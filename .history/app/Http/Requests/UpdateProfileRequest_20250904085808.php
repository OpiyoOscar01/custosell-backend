<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->user()->id;
        
        return [
            // Basic profile fields
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId)
            ],
            
            // Optional profile fields
            'phone' => 'nullable|string|max:20',
            'employee_id' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('users')->ignore($userId)
            ],
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|string|in:male,female,other',
            
            // Address fields
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            
            // Localization fields
            'timezone' => 'nullable|string|max:50',
            'locale' => 'nullable|string|max:10',
            
            // Work-related fields
            'hourly_rate' => 'nullable|numeric|min:0|max:9999.99',
            
            // Preferences
            'preferences' => 'nullable|array',
            'preferences.*' => 'string',
            
            // Password change (optional)
            'current_password' => 'sometimes|required_with:password|string',
            'password' => 'sometimes|required|string|min:8|confirmed',
        ];
    }

    /**
     * Get custom validation messages
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'email.required' => 'Email address is required',
            'email.email' => 'Please provide a valid email address',
            'email.unique' => 'This email address is already in use',
            'employee_id.unique' => 'This employee ID is already in use',
            'date_of_birth.before' => 'Date of birth must be in the past',
            'gender.in' => 'Gender must be male, female, or other',
            'hourly_rate.numeric' => 'Hourly rate must be a valid number',
            'hourly_rate.min' => 'Hourly rate cannot be negative',
            'hourly_rate.max' => 'Hourly rate cannot exceed 9999.99',
            'current_password.required_with' => 'Current password is required when changing password',
            'password.min' => 'New password must be at least 8 characters long',
            'password.confirmed' => 'Password confirmation does not match',
        ];
    }

    /**
     * Get custom attribute names
     */
    public function attributes(): array
    {
        return [
            'first_name' => 'first name',
            'last_name' => 'last name',
            'email' => 'email address',
            'phone' => 'phone number',
            'employee_id' => 'employee ID',
            'date_of_birth' => 'date of birth',
            'gender' => 'gender',
            'address' => 'address',
            'city' => 'city',
            'state' => 'state',
            'country' => 'country',
            'postal_code' => 'postal code',
            'timezone' => 'timezone',
            'locale' => 'locale',
            'hourly_rate' => 'hourly rate',
            'current_password' => 'current password',
            'password' => 'new password',
            'password_confirmation' => 'password confirmation',
        ];
    }
}
