<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    public function gates()
    {
        return $this->hasMany('App\Gate');
    }
}
