<?php

namespace App\Http\Requests;
use Auth;
use App\{ User, Vehicle, PersonVehicle };
use Illuminate\Foundation\Http\FormRequest;

class SaveVehicleRequest extends FormRequest
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
        return array_merge(
            Vehicle::getValidationRules(),
            PersonVehicle::getPeopleValidationRules()
        );
    }

    /**
     * Transforms the request input data before validation.
     * 
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge(['people_id' => json_decode($this->people_id)]);
    }
}
