<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePersonRequest extends FormRequest
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
            'cuil' => 'required|unique:people|string|min:10|max:15',
            'last_name' => 'required|string|max:50',
            'name' => 'required|string|max:50',
            'sex' => 'required|string|in:F,M,O',
            'birthday' => ['required','string',"regex:/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/"],
            'company_id' => 'required|integer|exists:companies,id'
        ];
    }
}
