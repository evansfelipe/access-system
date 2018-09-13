<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Storage;

use App\Location\{ City, Province, Country };

trait Helpers {

    public static function storeLocation($p_city, $p_province, $p_country)
    {
        if($p_country) {
            $country = Country::where('name', $p_country)->first();
            if(!$country) {
                $country = new Country(['name' => $p_country]);
                $country->save();
            }

            if($p_province) {
                $province = Province::where('name', $p_province)->where('country_id', $country->id)->first();
                if(!$province) {
                    $province = new Province(['name' => $p_province, 'country_id' => $country->id]);
                    $province->save();
                }

                if($p_city) {
                    $city = City::where('name', $p_city)->where('province_id', $province->id)->first();
                    if(!$city) {
                        $city = new City(['name' => $p_city, 'province_id' => $province->id]);
                        $city->save();
                    }
                }
            }
        }
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
        return $timestamp ? date('d-m-Y', strtotime($timestamp)) : '-';
    }

    public static function storeFile($path, $file)
    {
        $name = time() . '.' . $file->guessExtension();
        Storage::putFileAs($path, $file, $name);
        return $name;
    }


    public static function getImageAsDataURI($path)
    {
        $image = Storage::get($path);
        $mime  = Storage::mimeType($path);
        return 'data:' . $mime . ';base64,' . base64_encode($image);
    }

}