<?php

namespace App\Http\Requests;

use App\Models\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        if (($this->isMethod('patch') || $this->isMethod('delete')) && (!$this->id || !Admin::find($this->id))) {
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
        if (!$this->isMethod('delete')) {
            return [
                'name' => 'required|max:200',
                'school' => 'required|integer',
                'teacher' => 'required|integer',
            ];
        }
        return [];
    }
}
