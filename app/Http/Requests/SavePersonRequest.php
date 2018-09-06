<?php namespace App\Http\Requests;
use Auth;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\{ User, Person, Residency, PersonCompany, PersonVehicle, Card };

use ArrayObject;

class SavePersonRequest extends FormRequest
{
    /**
     * Prepare the request data to be validated.
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'vehicles_id'   =>  json_decode($this->vehicles_id),
            'jobs'          =>  array_map(function($job) {
                                    $job['company_id'] = !empty($job['company_id']) ? $job['company_id'] : null;
                                    return $job;
                                }, json_decode($this->jobs, true))
        ]);
    }

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
        $person_rules               = Person::getValidationRules();
        $residency_rules            = Residency::getValidationRules();
        $working_information_rules  = PersonCompany::getValidationRules();
        $assign_vehicles_rules      = PersonVehicle::getVehiclesValidationRules();

        if($this->route()->getName() === 'people.update') {
            if($this->cuil === $this->person->cuil && ($key = array_search('unique:people', $person_rules['cuil'])) !== false) {
                unset($person_rules['cuil'][$key]);
            }
            if(($key = array_search('required', $person_rules['picture'])) !== false) {
                unset($person_rules['picture'][$key]);
                $person_rules['picture'] = array_merge(['nullable'], $person_rules['picture']);
            }
        }

        $card_numbers = [];
        foreach($this->jobs as $job) {
            foreach($job['cards'] as $card) {
                array_push($card_numbers, $card['number']);
            }
        }

        foreach ($this->jobs as $jk => $job) {
            foreach ($job['cards'] as $ck => $card) {
                $working_information_rules['jobs.'.$jk.'.cards.'.$ck.'.until'] = array_merge(
                    $working_information_rules['jobs.*.cards.*.until'],
                    ['after_or_equal:'.'jobs.'.$jk.'.cards.'.$ck.'.from']
                );

                $card_numbers_copy = $card_numbers;
                if(($key = array_search($card['number'], $card_numbers_copy)) !== false) {
                    unset($card_numbers_copy[$key]);
                }

                $working_information_rules['jobs.'.$jk.'.cards.'.$ck.'.number'] = array_merge(
                    $working_information_rules['jobs.*.cards.*.number'],
                    ['not_in:'.implode(',', $card_numbers_copy)]
                );
            }
        }

        unset(
            $working_information_rules['jobs.*.cards.*.until'], 
            $working_information_rules['jobs.*.cards.*.number']
        );

        $rules = array_merge(
            $person_rules, 
            $residency_rules, 
            $working_information_rules, 
            $assign_vehicles_rules
        );

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'jobs.*.cards.*.until.required'         => 'El campo Valido hasta es obligatorio.',
            'jobs.*.cards.*.until.date'             => 'El campo Valido hasta debe ser una fecha.',
            'jobs.*.cards.*.until.regex'            => 'El campo Valido hasta tiene un formato no valido.',
            'jobs.*.cards.*.until.after_or_equal'   => 'El campo Valido hasta debe ser posterior al campo Valido desde.',
            'jobs.*.cards.*.number.unique'          => 'El valor del campo Número de tarjeta ya está en uso dentro del sistema.',
            'jobs.*.cards.*.number.not_in'          => 'Número de tarjeta repetido en este formulario.',
            'jobs.*.cards.*.number.required'        => 'El campo Número de tarjeta es obligatorio.',
            'jobs.*.cards.*.number.string'          => 'El campo Número de tarjeta debe ser una cadena de caracteres.',
        ];
    }
}
