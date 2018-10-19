<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Storage;

use App\Subactivity;
use App\Location\{ City, Province, Country };

trait Helpers {

    public static function storeSubactivity($name, $activity_id)
    {
        $ret = Subactivity::where('activity_id', $activity_id)->where('name', $name)->first();

        if(!$ret) {
            $ret = new Subactivity(['name' => $name, 'activity_id' => $activity_id]);
            $ret->save();
        }

        return $ret;
    }

    /**
     * 
     */
    public static function getCuilRules()
    {
        return [
            'string', 
            "regex:/^(20|23|24|27|30|33|34)-[0-9]{8}-[0-9]{1}$/",
            // function($attribute, $value, $fail) {
            //     $sum = 0;
            //     $digits = str_split(str_replace('-', '', $value));
            //     $validator_digit = array_pop($digits);
            //     for( $i = 0; $i < count($digits); $i++ ) {
            //         $sum += $digits[ 9 - $i ] * ( 2 + ($i % 6) );
            //     }
            //     $validator_number = 11 - ($sum % 11);
            //     $validator_number = ($validator_number === 11) ? 0 : $validator_number;
            //     if($validator_number != $validator_digit)
            //         return $fail('El código verificador del CUIL/CUIT es inválido');
            // }
        ];
    }

    public static function timestampToDate($timestamp)
    {
        return $timestamp ? date('d/m/Y', strtotime($timestamp)) : '-';
    }

    public static function storeFile($path, $fileAsDataURI)
    {
        list($meta, $content) = explode(',', $fileAsDataURI);
        $content = base64_decode($content);
        $name = uniqid();
        Storage::put($path.'/'.$name, $content);
        return $name;
    }


    public static function getImageAsDataURI($path)
    {
        $image = Storage::get($path);
        $mime  = Storage::mimeType($path);
        return 'data:' . $mime . ';base64,' . base64_encode($image);
    }

}