<?php

namespace App\Location;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = ['name', 'country_id'];
    
    public function country()
    {
        return $this->belongsTo('\App\Location\Country');
    }

    public function cities()
    {
        return $this->hasMany('\App\Location\City');
    }
}
