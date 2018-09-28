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
        $vehicle_rules = Vehicle::getValidationRules();
        if($this->route()->getName() === 'vehicles.update') {
            if($this->plate === $this->vehicle->plate && ($key = array_search('unique:vehicles', $vehicle_rules['plate'])) !== false) {
                unset($vehicle_rules['plate'][$key]);
            }
        }
        return array_merge(
            $vehicle_rules,
            PersonVehicle::getPeopleValidationRules()
        );
    }
}
