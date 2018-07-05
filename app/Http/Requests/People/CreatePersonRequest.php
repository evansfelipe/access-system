<?php

namespace App\Http\Requests\People;

use App\Person;

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
        $rules = Person::getValidationRules();

        array_push($rules['cuil'],       'required');
        array_push($rules['cuil'],       'unique:people');
        array_push($rules['last_name'],  'required');
        array_push($rules['name'],       'required');
        array_push($rules['sex'],        'required');
        array_push($rules['birthday'],   'required');
        array_push($rules['company_id'], 'required');

        return $rules;
    }
}
