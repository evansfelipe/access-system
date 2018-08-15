<?php namespace App\Http\Requests;
use Auth;
use Illuminate\Foundation\Http\FormRequest;
use App\{ User, Person, Residency, PersonCompany, PersonVehicle, Card };

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

        if($this->route()->getName() === 'people.update') {
            if($this->cuil === $this->person->cuil && ($key = array_search('unique:people', $person_rules['cuil'])) !== false) {
                unset($person_rules['cuil'][$key]);
            }
            if(($key = array_search('required', $person_rules['picture'])) !== false) {
                unset($person_rules['picture'][$key]);
                $person_rules['picture'] = array_merge(['nullable'], $person_rules['picture']);
            }
        }

        return array_merge(
            $person_rules, 
            $residency_rules, 
            $working_information_rules, 
            $assign_vehicles_rules, 
            $first_card_rules
        );
    }

    /**
     * Transforms the request input data before validation.
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge(['vehicles_id' => json_decode($this->vehicles_id)]);
    }
}
