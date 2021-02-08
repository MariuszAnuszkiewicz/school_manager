<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class SaveMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'message' => 'required|string|max:255',
            'selected' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'message.required' => 'you must fill in message field',
            'selected.required' => 'you must check at least one checkbox.',
        ];
    }
}
