<?php

namespace App\Http\Requests;

use App\Models\Section;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        if (($this->isMethod('patch') || $this->isMethod('delete')) && (!$this->id || !Section::find($this->id))) {
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
            $rules = [
                'name' => 'required|max:200',
                'school' => 'required',
            ];
        
            if (strpos(request()->url(), '/teacher/') !== false) {
                unset($rules['teacher']);
            } else {
                $rules['teacher'] = 'required';
            }
        
            return $rules;
        }
        return [];
    }
}
