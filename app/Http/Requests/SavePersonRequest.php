<?php

namespace App\Http\Requests;
use Auth;
use App\User;
use App\Person;
use App\Residency;
use App\PersonCompany;
use App\PersonVehicle;
use App\Card;
use Illuminate\Foundation\Http\FormRequest;

class SavePersonRequest extends FormRequest
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
        $residency_rules = Residency::getValidationRules();
        $working_information_rules = PersonCompany::getValidationRules();
        $assign_vehicles_rules = PersonVehicle::getValidationRules();
        $first_card_rules = Card::getValidationRules();

        if($this->route()->getName() === 'people.update' && $this->cuil === $this->person->cuil) {
            if (($key = array_search('unique:people', $person_rules['cuil'])) !== false) {
                unset($person_rules['cuil'][$key]);
            }
        }

        $rules = array_merge($person_rules, $residency_rules, $working_information_rules, $assign_vehicles_rules, $first_card_rules);

        return $rules;
    }
}
