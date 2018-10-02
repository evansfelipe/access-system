<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gate extends Model
{
    protected $fillable = ['name', 'enabled'];
    
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
            ]
        ];
    }

    public function toListArray()
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'enabled'   => $this->enabled
        ];
    }
}
