<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => 'sometimes|required|integer|exists:companies,id',
            'name' => 'sometimes|required|string|max:255',
            'short_name' => 'sometimes|required|string|max:50',
            'symbol' => 'nullable|string|max:10',
            'type' => 'sometimes|required|string|in:weight,length,volume,quantity,time',
            'allow_decimal' => 'boolean',
            'is_active' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required' => 'Company is required',
            'company_id.exists' => 'Selected company does not exist',
            'name.required' => 'Unit name is required',
            'short_name.required' => 'Unit short name is required',
            'type.required' => 'Unit type is required',
            'type.in' => 'Unit type must be one of: weight, length, volume, quantity, time'
        ];
    }
}
