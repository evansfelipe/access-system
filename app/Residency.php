<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Residency extends Model
{
    public static function getValidationRules()
    {
        return [
            'street' => ['string', 'max:256', 'nullable'],
            'apartment' => ['string', 'max:15', 'nullable'],
            'cp' => ['string', 'max:10', 'nullable'],
            'country' => ['string', 'nullable'],
            'province' => ['string', 'nullable'],
            'city' => ['string', 'nullable'],
        ];
    }

    public function person()
    {
        return $this->belongsTo('App\Person');
    }
}
