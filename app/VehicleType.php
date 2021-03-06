<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    protected $fillable = ['type', 'allows_container'];
    
    /**
     * Array with the length of each string column of the database associated with this model.
     */
    public const LENGTHS = [
        'type' => ['max' => 30]
    ];

    /**
     * Creates and returns an array with the validation rules for each attribute that can be
     * entered through an input (or other html component) by the user 
     */
    public static function getValidationRules()
    {
        return [
            'type' => [
                'required',
                'string',
                'max:'.VehicleType::LENGTHS['type']['max'],
                'unique:vehicle_types'
            ],
            'allows_container' => [
                'required',
                'boolean'
            ]
        ];
    }

    public function vehicles()
    {
        return $this->hasMany('App\Vehicle', 'type_id');
    }

    public function toListArray()
    {
        return [
            'id'                => $this->id,
            'type'              => $this->type,
            'allows_container'  => $this->allows_container
        ];
    }
}
