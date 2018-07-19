<?php

namespace App\Http\Requests;

use App\Card;
use Illuminate\Foundation\Http\FormRequest;

class SaveCardRequest extends FormRequest
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
        $rules = Card::getValidationRules();

        array_push($rules['person_id'], 'required');
        
        return $rules;
    }
}
