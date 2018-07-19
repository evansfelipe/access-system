<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    public const LENGTHS = [
        'plate' => ['min' => 5 ,'max' => 10],
        'brand' => ['max' => 20],
        'model' => ['max' => 30],
        'colour' => ['max' => 15]
    ];

    /**
     * Creates and returns an array with the validation rules for each attribute that can be
     * entered through an input (or other html component) by the user 
     */
    public static function getValidationRules()
    {
        return [
            'company_id' => [
                'required',
                'exists:companies,id'
            ],
            'plate' => [
                'string',
                'required',
                'min:'.Vehicle::LENGTHS['plate']['min'],
                'max:'.Vehicle::LENGTHS['plate']['max']
            ],
            'brand' => [
                'string',
                'required',
                'max:'.Vehicle::LENGTHS['brand']['max']
            ],
            'model' => [
                'string',
                'required',
                'max:'.Vehicle::LENGTHS['model']['max']
            ],
            'colour' => [
                'string',
                'nullable',
                'max:'.Vehicle::LENGTHS['colour']['max']
            ],
            'year' => [
                // Validate that is a year
                'nullable',
            ]
        ];
    }

    public function people()
    {
        return $this->belongsToMany('\App\Person', 'person_vehicles')->using('\App\PersonVehicle');
    }
}
