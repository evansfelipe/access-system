<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PersonVehicle extends Pivot
{
    public const LENGTHS = [];
    protected $fillable = ['person_id', 'vehicle_id'];

    public static function getVehiclesValidationRules()
    {
        return [
            'vehicles_id' => [
                'array'
            ],
            'vehicles_id.*' => [
                'integer',
                'exists:vehicles,id'
            ]
        ];
    }

    public static function getPeopleValidationRules()
    {
        return [
            'people_id' => [
                'array'
            ],
            'people_id.*' => [
                'integer',
                'exists:people,id'
            ]
        ];
    }
}
