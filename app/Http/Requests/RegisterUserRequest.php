<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
        return [
            // Required fields
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',

            // Optional profile fields
            'phone' => 'nullable|string|max:20',
            'employee_id' => 'nullable|string|max:50|unique:users',
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
            'is_active' => 'nullable|boolean',
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
            'email.unique' => 'This email address is already registered',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters long',
            'password.confirmed' => 'Password confirmation does not match',
            'employee_id.unique' => 'This employee ID is already in use',
            'date_of_birth.before' => 'Date of birth must be in the past',
            'gender.in' => 'Gender must be male, female, or other',
            'hourly_rate.numeric' => 'Hourly rate must be a valid number',
            'hourly_rate.min' => 'Hourly rate cannot be negative',
            'hourly_rate.max' => 'Hourly rate cannot exceed 9999.99',
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
            'password' => 'password',
            'password_confirmation' => 'password confirmation',
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
            'is_active' => 'active status',
        ];
    }
}
