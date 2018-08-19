<?php namespace App\Http\Traits;
use Illuminate\Http\Request;

trait Helpers {
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
}