<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TableTimestamp extends Model
{
    public static function lastTimestamp($table)
    {
        $mt = TableTimestamp::select('last')->where('table_name', $table)->first();
        return $mt ? $mt->last : null;
    }
}
