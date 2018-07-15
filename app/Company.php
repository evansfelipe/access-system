<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public const LENGTHS = [
        'name' => ['max' => 50],
        'area' => ['max' => 50 ],
        'cuit' => ['max' => 15, 'min' => 10],
    ];

    public static function getValidationRules()
    {
        return [
            'name' => [
                'string',
                'max:'.Company::LENGTHS['name']['max'],
                'required'
            ],
            'area' => [
                'string',
                'max:'.Company::LENGTHS['area']['max'],
                'required'
            ],
            'cuit' => [
                'string',
                'min:'.Company::LENGTHS['cuit']['min'],
                'max:'.Company::LENGTHS['cuit']['max'],
                'required'
            ],
            'expiration' => [
                'required',
                'date',
                "regex:/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", 
                'after:'.date('Y-m-d'), 
            ],
            'phone' => [
                'string',
            ],
            'fax' => [
                'string',
                'nullable'             
            ],
            'email' => [
                'string',
            ],
            'web' => [
                'string',
                'nullable'
            ]                                                                       
        ];
    }

    public function people()
    {
        return $this->belongsToMany('App\Person');
    }
}
