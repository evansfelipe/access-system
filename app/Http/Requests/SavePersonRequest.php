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
        \Debugbar::info($this->json);
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

        // foreach(array_merge($person_rules, Residency::getValidationRules()) as $key => $value) {
        //     $rules['personal_information.'.$key] = $value;
        // }

        // foreach(PersonCompany::getValidationRules() as $key => $value) {
        //     $rules['working_information.'.$key] = $value;
        // }

        // foreach(PersonVehicle::getValidationRules() as $key => $value) {
        //     $rules['assign_vehicles.'.$key] = $value;
        // }

        // foreach(Card::getValidationRules() as $key => $value) {
        //     $rules['first_card.'.$key] = $value;
        // }

        // $rules = [
        //     'personal_information' => array_merge($personal_information_rules, $residency_rules),
        //     'working_information'  => $working_information_rules,
        //     'assign_vehicles'      => $assign_vehicles_rules,
        //     'first_card'           => $first_card
        // ];

        // \Debugbar::info($rules);

        return $rules;


        // $person_rules = Person::getValidationRules();
        // if($this->route()->getName() === 'people.update' && $this->cuil === $this->person->cuil) {
        //     if (($key = array_search('unique:people', $person_rules['cuil'])) !== false) {
        //         unset($person_rules['cuil'][$key]);
        //     }
        // }
        // return array_merge(
        //     $person_rules,
        //     $residency_rules
        // );
    }
}
