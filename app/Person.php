<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{

    public const DNI = 0;
    public const PASSPORT = 1;

    public const LENGTHS = [
        'last_name' => ['max' => 50],
        'name' => ['max' => 50],
        'document_number' => ['min' => 7, 'max' => 12],
        'cuil' => ['min' => 10, 'max' => 15],
        'pna' => ['min' => 10, 'max' => 15]
    ];

    public static function getValidationRules()
    {
        return [
            'last_name' => [
                'string',
                'max:'.Person::LENGTHS['last_name']['max']
            ],
            'name' => [
                'string', 
                'max:'.Person::LENGTHS['name']['max']
            ],
            'document_type' => [
                'integer', 
                'in:'.Person::DNI.','.Person::PASSPORT
            ],
            'document_number' => [
                'string', 
                'min:'.Person::LENGTHS['document_number']['min'], 
                'max:'.Person::LENGTHS['document_number']['max']
            ],
            'cuil' => [
                'string', 
                'min:'.Person::LENGTHS['cuil']['min'], 
                'max:'.Person::LENGTHS['cuil']['max']
            ],
            'birthday' => [
                'string', 
                "regex:/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", 
                'after:'.date('1900-01-01'), 
                'before:'.date('Y-m-d'), 
                'nullable'
            ],
            'sex' => [
                'string', 
                'in:F,M,O', 
                'nullable'
            ],
            'blood_type' => [
                'string', 
                'in:0-,0+,A-,A+,B-,B+,AB-,AB+', 
                'nullable'
            ],
            'picture' => [
                'image', 
                'mimes:jpeg,jpg,png'
            ],
            'pna' => [
                'string', 
                'min:'.Person::LENGTHS['pna']['min'], 
                'max:'.Person::LENGTHS['pna']['max'],
                'nullable'
            ],
            'email' => [
                'email',
                'nullable'
            ],
            'home_phone' => [
                'string',
                'nullable'
            ],
            'mobile_phone' => [
                'string',
                'nullable'
            ],
            'fax' => [
                'string',
                'nullable'
            ]
        ];
    }

    /**
     * Get all the cards associated with a person.
     */
    public function cards()
    {
        return $this->hasMany('App\Card');
    }

    public function companies()
    {
        return $this->belongsToMany('App\Company');
    }

    public function company()
    {
        return $this->companies()->first();
    }

    public function residency()
    {
        return $this->hasOne('App\Residency');
    }

    public function fullName() 
    {
        return $this->last_name . ', ' . $this->name;
    }

    public function sexToString()
    {
        switch($this->sex)
        {
            case "F":
                return "Femenino";
            case "M":
                return "Masculino";
            case "O":
                return "Otro sexo";
        }
    }

    public function getActiveCard()
    {
        return $this->cards()->where('active','=',true)->first();
    }

    public function getInactiveCards()
    {
        return $this->cards()->where('active','=',false)->orderBy('updated_at','desc')->get();
    }

    public function toArray()
    {
        return [
            'last_name' => $this->last_name,
            'name' => $this->name,
            'cuil' => $this->cuil,
            'company_name' => $this->company->name,
            'url' => route('people.show', $this->id)
        ];
    }
}
