<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = ['number', 'risk', 'active', 'from', 'until'];
    /**
     * Creates and returns an array with the validation rules for each attribute that can be
     * entered through an input (or other html component) by the user 
     */
    public static function getValidationRules()
    {
        return [
            'number' => [
                'required',
                'string',
            ],
            'person_id' => [
                'integer',
                'exists:people,id'
            ],
            'risk' => [
                'required',
                'integer',
            ],
            'from' => [
                'required',
                'date',
                "regex:/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", 
                'after_or_equal:'.date('Y-m-d'), 
            ],
            'until' => [
                'required',
                'date',
                "regex:/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", 
                'after_or_equal:from'
            ],
        ];
    }

    /**
     * Get the card owner.
     */
    public function owner()
    {
        return $this->belongsTo('App\Person');
    }

    public function toArray()
    {
        return [
            'number' => $this->number,
            'risk' => 'Nivel ' . $this->risk,
            'from' => date('d-m-Y', strtotime($this->from)),
            'until' => date('d-m-Y', strtotime($this->until))
        ];
    }
}
