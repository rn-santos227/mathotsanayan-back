<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|max:50|string',
            'middle_name' => 'max:50|string|nullable',
            'last_name' => 'required|max:50|string',
            'suffix' => 'max:5|string|nullable',
            'contact_number' => 'max:50|string|nullable',
            'student_number' => 'required|max:50|string|nullable',
            'course' => 'required|integer',
            'school' => 'required|integer',
            'section' => 'required|integer',
            'password' => 'required|min:6|max:50',
        ];
    }
}
