<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Residency extends Model
{
    /**
     * Array with the length of each string column of the database associated with this model.
     */
    public const LENGTHS = [
        'cp' => ['max' => 10],
        'apartment' => ['max' => 15]
    ];

    /**
     * Creates and returns an array with the validation rules for each attribute that can be
     * entered through an input (or other html component) by the user 
     */
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

    /**
     * Gets the person associated with this residency.
     * As a residency can be associated with a person or with a company, this return can be null.
     */
    public function person()
    {
        return $this->belongsTo('App\Person');
    }

    /**
     * Gets the company associated with this residency.
     * As a residency can be associated with a person or with a company, this return can be null.
     */
    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function toString()
    {
        $string = '';
        $string .= $this->street    ? strval($this->street).' ' : '';
        $string .= $this->apartment ? strval($this->apartment).' ' : '';
        $string .= $this->city      ? strval($this->city).' ' : '';
        $string .= $this->province  ? strval($this->province).' ' : '';
        $string .= $this->country   ? strval($this->country).' ' : '';
        $string .= $this->cp        ? strval($this->cp).' ' : '';
        return $string;
    }
}
