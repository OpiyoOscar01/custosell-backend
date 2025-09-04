<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $brandId = $this->route('brand');
        
        return [
            'company_id' => 'sometimes|required|integer|exists:companies,id',
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|string|max:255|unique:brands,slug,' . $brandId,
            'description' => 'nullable|string',
            'logo' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'is_active' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'company_id.required' => 'Company is required',
            'company_id.exists' => 'Selected company does not exist',
            'name.required' => 'Brand name is required',
            'name.max' => 'Brand name cannot exceed 255 characters',
            'slug.unique' => 'Brand slug must be unique',
            'website.url' => 'Please provide a valid website URL'
        ];
    }
}
