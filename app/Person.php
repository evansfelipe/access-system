<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{

    public const DNI = 0;
    public const PASSPORT = 1;

    public static function getValidationRules()
    {
        return [
            'last_name' => ['string', 'max:50'],
            'name' => ['string', 'max:50'],
            'document_type' => ['string', 'in:dni,passport'],
            'document_number' => ['string', 'min:7', 'max:12'],
            'cuil' => ['string', 'min:10', 'max:15'],
            'birthday' => ['string', "regex:/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", 'after:'.date('1900-01-01'), 'before:'.date('Y-m-d'), 'nullable'],
            'sex' => ['string', 'in:F,M,O', 'nullable'],
            'blood_type' => ['string', 'in:0-,0+,A-,A+,B-,B+,AB-,AB+', 'nullable'],
            'picture' => ['image', 'mimes:jpeg,jpg,png']
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
