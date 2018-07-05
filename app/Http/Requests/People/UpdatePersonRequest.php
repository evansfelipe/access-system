<?php

namespace App\Http\Requests\People;

use App\Person;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonRequest extends FormRequest
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
        $rules = Person::getValidationRules();
        if($this->cuil != $this->person->cuil) {
            array_push($rules['cuil'], 'unique:people');
        }
        return $rules;
    }
}
