<?php

namespace App\Http\Requests;

use App\Models\Result;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResultRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!$this->id) {
            return false;
        }

        if (($this->isMethod('patch') || $this->isMethod('delete')) && (!$this->id || !Result::find($this->id))) {
            throw new HttpResponseException(response([
                'error' => 'Illegal Access',
            ], 500));
        }

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
            //
        ];
    }
}
