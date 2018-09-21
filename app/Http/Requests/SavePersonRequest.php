<?php namespace App\Http\Requests;
use Auth;
use Illuminate\Validation\Rule;
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
        \Debugbar::info($this);
        $person_rules               = Person::getValidationRules();
        $residency_rules            = Residency::getValidationRules();
        $working_information_rules  = PersonCompany::getValidationRules();
        $assign_vehicles_rules      = PersonVehicle::getVehiclesValidationRules();
        $documents_required_rules   = [
            'documents_required' => [
                'required',
                'array',
                function($attribute, $value, $fail) {
                    $keys_allowed = [
                        'acc_pers',
                        'art_file',
                        'boarding_card',
                        'boarding_passbook',
                        'company_note',
                        'dni_copy',
                        'driver_license',
                        'health_notebook',
                        'pbip_file',
                        'pna_file'
                    ];
                    foreach (array_keys($value) as $key) {
                        if(!in_array($key, $keys_allowed)) {
                            return $fail('Algo salió mal al validar los datos. Por favor, intente de nuevo o reinicie el sistema');
                        }
                    }
                }
            ],
            'documents_required.*' => [
                'boolean'
            ]
        ];
        /**
         * If the request comes from an edition, then performs some actions over the personal information validation rules.
         */
        if($this->route()->getName() === 'people.update') {
            /**
             * If the cuil hadn't been changed, then removes the unique rule, otherwise (as the cuil is already saved) the request would fail.
             */
            if($this->cuil === $this->person->cuil && ($key = array_search('unique:people', $person_rules['cuil'])) !== false) {
                unset($person_rules['cuil'][$key]);
            }
            /**
             * As the person had been saved previously, already has (at least) one picture saved on the server.
             * For that, removes the required rule from the picture field, and adds the nullable one. This prevents
             * sending a new picture each time the person is edited.
             */
            if(($key = array_search('required', $person_rules['picture'])) !== false) {
                unset($person_rules['picture'][$key]);
                $person_rules['picture'] = array_merge(['nullable'], $person_rules['picture']);
            }
        }
        /**
         * Gets each card number sent in this request. It may have repeated values.
         */
        $card_numbers = [];
        foreach($this->jobs as $job) {
            foreach($job['cards'] as $card) {
                array_push($card_numbers, $card['number']);
            }
        }
        /**
         * Performs some actions over the working information validation rules.
         */
        foreach ($this->jobs as $jk => $job) {
            foreach ($job['cards'] as $ck => $card) {
                /**
                 * Adds the after_or_equal validation rule so the until date for the card is greater or equals to
                 * the from date. It's needed to perform this action with each card because Laravel (<= 5.6) doesn't
                 * allow to check field against field inside the same array's element.
                 */
                $working_information_rules['jobs.'.$jk.'.cards.'.$ck.'.until'] = array_merge(
                    $working_information_rules['jobs.*.cards.*.until'],
                    ['after_or_equal:'.'jobs.'.$jk.'.cards.'.$ck.'.from']
                );
                /**
                 * Creates a copy of the cards numbers array, and removes the first appearance of the number
                 * of the card that is under iteration. This number it's present at least one time, but may
                 * be present more than once. If that is the case, just removes one (first) appearance.
                 */
                $card_numbers_copy = $card_numbers;
                if(($key = array_search($card['number'], $card_numbers_copy)) !== false) {
                    unset($card_numbers_copy[$key]);
                }
                /**
                 * Adds the not_in validation rule with the remaining cards numbers to the number of the card under iteration.
                 * If the number is still in there, then the validation will fail because the number was repeated.
                 */
                $working_information_rules['jobs.'.$jk.'.cards.'.$ck.'.number'] = array_merge(
                    $working_information_rules['jobs.*.cards.*.number'],
                    [Rule::notIn($card_numbers_copy)]
                );
            }
        }
        /**
         * Removes some rules that (with the changes applied previously) are useless.
         */
        unset($working_information_rules['jobs.*.cards.*.until'], $working_information_rules['jobs.*.cards.*.number']);
        /**
         * Merges and returns an unique array with each validation rule.
         */
        return array_merge(
            $person_rules, 
            $residency_rules, 
            $working_information_rules, 
            $assign_vehicles_rules,
            $documents_required_rules
        );
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
