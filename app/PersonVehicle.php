<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PersonVehicle extends Pivot
{
    public const LENGTHS = [];

    public static function getValidationRules()
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
}
