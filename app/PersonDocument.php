<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonDocument extends Model
{
    public const company_note = 0;
    public const dni_copy = 1;
    public const pna_file = 2;
    public const driver_license = 3;
    public const art_file = 4;
    public const acc_pers = 5;
    public const boarding_passbook = 6;
    public const boarding_card = 7;
    public const health_notebook = 8;
    public const pbip_file = 9;

    public function getConst($string)
    {
        return constant('App\PersonDocument::' . $string);
    }
}
