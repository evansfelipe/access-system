<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gate extends Model
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
                'unique:gates,name',
                'string',
                'max:'.Gate::LENGTHS['name']['max'],
            ],
            'enabled' => [
                'required',
                'boolean'
            ],
            'zone_id' => [
                'required',
                'integer',
                'exists:zones,id'
            ]
        ];
    }

    /**
     * All the groups where this gate is assigned. It may be empty if this gate hasn't been assigned
     * to a group yet.
     */
    public function groups()
    {
        return $this->hasMany('App\Group');
    }

    public function toListArray()
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'enabled'   => $this->enabled,
            'zone_id'   => $this->zone_id
        ];
    }
}