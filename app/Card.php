<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
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
