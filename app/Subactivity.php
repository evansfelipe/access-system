<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subactivity extends Model
{
    protected $fillable = ['name', 'activity_id'];
    
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
                'string',
                'max:'.Subactivity::LENGTHS['name']['max'],
                'unique_with:subactivities,activity_id'
            ],
            'activity_id' => [
                'required',
                'integer',
                'exists:activities,id'
            ]
        ];
    }

    public function activity()
    {
        return $this->belongsTo('\App\Activity');
    }

    public function toListArray()
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'activity_id'   => $this->activity_id
        ];
    }
}
