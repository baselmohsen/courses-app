<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
     public function authorize(): bool
    {
        $course = $this->route('course');

        return $course && (
            auth()->user()->id === $course->instructor_id
            || auth()->user()->role === 'admin'
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        'title' => 'sometimes|string',
        'description' => 'sometimes|string',
        'price' => 'sometimes|numeric',
        'category_id' => 'sometimes|exists:categories,id',
        'thumbnail' => 'sometimes|string',
        'status' => 'sometimes|in:draft,pending,published'
    ];
    }
}
