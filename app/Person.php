<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    public static function getValidationRules()
    {
        return [
            'last_name' => ['string','max:50'],
            'name' => ['string','max:50'],
            'cuil' => ['string','min:10','max:15'],
            'sex' => ['string','in:F,M,O'],
            'company_id' => ['integer','exists:companies,id'],
            'birthday' => ['string',"regex:/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",'after:'.date('1900-01-01'),'before:'.date('Y-m-d')],
            'picture' => ['image','mimes:jpeg,jpg,png']
        ];
    }

    /**
     * Get all the cards associated with a person.
     */
    public function cards()
    {
        return $this->hasMany('App\Card');
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
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
