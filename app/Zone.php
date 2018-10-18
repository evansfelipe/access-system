<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    protected $fillable = ['name', 'enabled', 'zone_id'];
    
    /**
     * Array with the length of each string column of the database associated with this model.
     */
    public const LENGTHS = [
        'name' => ['max' => 50]
    ];

    /**
     * Creates and returns an array with the validation rules for each attribute that can be
     * entered through an input (or other html component) by the user 
     */
    public static function getValidationRules()
    {
        return [
            'name' => [
                'required',
                'unique:zones,name',
                'string',
                'max:'.Zone::LENGTHS['name']['max'],
            ]
        ];
    }
    
    public function gates()
    {
        return $this->hasMany('App\Gate');
    }

    public function groups()
    {
        return $this->hasMany('App\Group');
    }

    public function toListArray()
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
        ];
    }
}
