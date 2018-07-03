<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public function people()
    {
        return $this->hasMany('App\Person');
    }
}
