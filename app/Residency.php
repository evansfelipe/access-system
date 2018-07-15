<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Residency extends Model
{
    public const LENGTHS = [
        'cp' => ['max' => 10],
        'apartment' => ['max' => 15]
    ];

    public static function getValidationRules()
    {
        return [
            'street' => [
                'string',
                'max:256',
                'nullable'
            ],
            'apartment' => [
                'string', 
                'max:'.Residency::LENGTHS['apartment']['max'], 
                'nullable'
            ],
            'cp' => [
                'string', 
                'max:'.Residency::LENGTHS['cp']['max'], 
                'nullable'
            ],
            'country' => [
                'string', 
                'max:256',
                'nullable'
            ],
            'province' => [
                'string', 
                'max:256',
                'nullable'
            ],
            'city' => [
                'string', 
                'max:256',
                'nullable'
            ],
        ];
    }

    public function person()
    {
        return $this->belongsTo('App\Person');
    }
}
