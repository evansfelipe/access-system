<?php

namespace App\Http\Requests\People;
use Auth;
use App\User;
use App\Person;
use App\Residency;
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
        return !Auth::guest() && (Auth::user()->type === User::ADMINISTRATION || Auth::user()->type === User::ROOT);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $person_rules = Person::getValidationRules();
        $resPersonidency_rules = Residency::getValidationRules();

        array_push($person_rules['last_name'], 'required');
        array_push($person_rules['name'], 'required');
        array_push($person_rules['document_type'], 'required');
        array_push($person_rules['document_number'], 'required');
        array_push($person_rules['cuil'], 'required');
        array_push($person_rules['cuil'], 'unique:people');

        $rules = array_merge($person_rules, $resPersonidency_rules);
        
        return $rules;
    }
}
