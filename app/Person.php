<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{

    public const DNI = 0;
    public const PASSPORT = 1;

    /**
     * Array with the length of each string column of the database associated with this model.
     */
    public const LENGTHS = [
        'last_name' => ['max' => 50],
        'name' => ['max' => 50],
        'document_number' => ['min' => 7, 'max' => 12],
        'cuil' => ['min' => 10, 'max' => 15],
        'pna' => ['min' => 10, 'max' => 15]
    ];

    /**
     * Creates and returns an array with the validation rules for each attribute that can be
     * entered through an input (or other html component) by the user 
     */
    public static function getValidationRules()
    {
        return [
            'last_name' => [
                'required',
                'string',
                'max:'.Person::LENGTHS['last_name']['max']
            ],
            'name' => [
                'required',
                'string', 
                'max:'.Person::LENGTHS['name']['max']
            ],
            'document_type' => [
                'required',
                'integer', 
                'in:'.Person::DNI.','.Person::PASSPORT
            ],
            'document_number' => [
                'required',
                'string', 
                'min:'.Person::LENGTHS['document_number']['min'], 
                'max:'.Person::LENGTHS['document_number']['max']
            ],
            'cuil' => [
                'required',
                'unique:people',
                'string', 
                'min:'.Person::LENGTHS['cuil']['min'], 
                'max:'.Person::LENGTHS['cuil']['max']
            ],
            'birthday' => [
                'string', 
                "regex:/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", 
                'after:'.date('1900-01-01'), 
                'before:'.date('Y-m-d'), 
                'nullable'
            ],
            'sex' => [
                'string', 
                'in:F,M,O', 
                'nullable'
            ],
            'blood_type' => [
                'string', 
                'in:0-,0+,A-,A+,B-,B+,AB-,AB+', 
                'nullable'
            ],
            'picture' => [
                'image', 
                'mimes:jpeg,jpg,png'
            ],
            'pna' => [
                'string', 
                'min:'.Person::LENGTHS['pna']['min'], 
                'max:'.Person::LENGTHS['pna']['max'],
                'nullable'
            ],
            'email' => [
                'email',
                'nullable'
            ],
            'home_phone' => [
                'string',
                'nullable'
            ],
            'mobile_phone' => [
                'string',
                'nullable'
            ],
            'fax' => [
                'string',
                'nullable'
            ]
        ];
    }

    /**
     * Gets each card associated with this person.
     */
    public function cards()
    {
        return $this->hasMany('App\Card');
    }

    /**
     * Gets each company associated with this person.
     */
    public function companies()
    {
        return $this->belongsToMany('App\Company');
    }

    /**
     * Gets the most recently company associated with this person.
     */
    public function company()
    {
        return $this->companies()->orderBy('created_at', 'desc')->first();
    }

    /**
     * Gets the residency associated with this person.
     */
    public function residency()
    {
        return $this->hasOne('App\Residency');
    }

    /**
     * Creates and returns a string with the last name and the name of this person.
     */
    public function fullName() 
    {
        return $this->last_name . ', ' . $this->name;
    }

    /**
     * Returns a string that represents the sex associated with this person.
     */
    public function sexToString()
    {
        switch($this->sex)
        {
            case "F":
                return "Femenino";
            case "M":
                return "Masculino";
            case "O":
                return "Otro sexo";
        }
    }

    public function documentTypeToString()
    {
        switch($this->document_type)
        {
            case Person::DNI:
                return "DNI";
            case Person::PASSPORT:
                return "Pasaporte";
        }
    }

    /**
     * Gets the active card associated with this person. If there is an active card, it should be unique;
     * this means a same person can not have more than one active card associated with him/her.
     */
    public function getActiveCard()
    {
        return $this->cards()->where('active','=',true)->first();
    }

    /**
     * Gets an array with each inactive card associated with this person.
     */
    public function getInactiveCards()
    {
        return $this->cards()->where('active','=',false)->orderBy('updated_at','desc')->get();
    }

    /**
     * Returns an array (that can be encoded to a json) that contains the data needed to be shown on the views of the app.
     */
    public function toArray()
    {
        $contact = json_decode($this->contact);
        $residency = \App\Residency::find($this->residency_id);
        return [
            'id' => $this->id,
            'full_name' => $this->fullName(),
            'document_type' => $this->documentTypeToString(),
            'document_number' => $this->document_number,
            'cuil' => $this->cuil,
            'birthday' => date('d-m-Y', strtotime($this->birthday)),
            'sex' => $this->sexToString(),
            'blood_type' => $this->blood_type,
            'url' => route('people.show', $this->id),
            'pna' => $this->pna,
            'email' => $contact->email ?? '',
            'phone' => $contact->home_phone ?? '',
            'mobile_phone' => $contact->mobile_phone ?? '',
            'fax' => $contact->fax ?? '',
            'residency' => $residency->toString(),
            'url' => route('people.show', $this->id)
        ];
    }
}
