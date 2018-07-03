<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
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
}
