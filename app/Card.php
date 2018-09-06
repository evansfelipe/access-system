<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = ['number', 'active', 'from', 'until'];
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
                'unique:cards,number'
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

    public function job()
    {
        return $this->belongsTo('App\PersonCompany', 'person_company_id');
    }

    public function toArray()
    {
        return [
            'number' => $this->number,
            'from' => date('d-m-Y', strtotime($this->from)),
            'until' => date('d-m-Y', strtotime($this->until))
        ];
    }
}
