<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         return [
        'phone' => 'nullable|string',
        'address' => 'nullable|string',
        'bio' => 'nullable|string',
        'avatar' => 'nullable|string',
        'date_of_birth' => 'nullable|date',
        'social_links' => 'nullable|array'
    ];
    }
}
