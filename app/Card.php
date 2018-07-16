<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    /**
     * Creates and returns an array with the validation rules for each attribute that can be
     * entered through an input (or other html component) by the user 
     */
    public static function getValidationRules()
    {
        return [
            'person_id' => ['integer','exists:people,id']
        ];
    }

    /**
     * Get the card owner.
     */
    public function owner()
    {
        return $this->belongsTo('App\Person');
    }
}
