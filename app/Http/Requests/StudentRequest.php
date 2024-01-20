<?php

namespace App\Http\Requests;

use App\Models\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        if (($this->isMethod('patch') || $this->isMethod('delete')) && (!$this->id || !Student::find($this->id))) {
            throw new HttpResponseException(response([
                'error' => 'Illegal Access',
            ], 500));
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('post')) {
            return [
                'first_name' => 'required|max:50|string',
                'middle_name' => 'max:50|string|nullable',
                'last_name' => 'required|max:50|string',
                'suffix' => 'max:5|string|nullable',
                'contact_number' => 'max:50|string|nullable',
                'student_number' => 'required|max:50|string|nullable',
                'course' => 'required|integer',
                'email' => 'required|unique:students',
                'school' => 'required|integer',
                'section' => 'required|integer',
                'password' => 'required|min:6|max:50',
            ];
        } else if ($this->isMethod('patch')) {
            return [
                'first_name' => 'required|max:50|string',
                'middle_name' => 'max:50|string|nullable',
                'last_name' => 'required|max:50|string',
                'suffix' => 'max:5|string|nullable',
                'contact_number' => 'max:50|string|nullable',
                'student_number' => 'required|max:50|string|nullable',
                'email' => 'required',
                'school' => 'required',
                'section' => 'required',
            ];
        } else {
            return [];
        }
    }
}
