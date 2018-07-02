<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    /**
     * Get the card owner.
     */
    public function owner()
    {
        return $this->belongsTo('App\Person');
    }
}
