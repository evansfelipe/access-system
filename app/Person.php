<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\{ Residency, Activity, Vehicle };

class Person extends Model
{
    protected $fillable = ['name', 'last_name', 'document_type', 'document_number', 'sex', 'pna', 'cuil', 'birthday', 'blood_type'];

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
                'required',
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
        return $this->belongsToMany('App\Company', 'company_people')->using('App\PersonCompany')->withPivot('activity_id','art','pbip');
    }

    /**
     * Gets the most recently company associated with this person.
     */
    public function company()
    {
        return $this->companies()->orderBy('created_at', 'desc')->first();
    }

    /**
     * Gets the most recently company associated with this person.
     */
    public function workingInformation()
    {
        return $this->company()->pivot;
    }

    /**
     * Gets the residency associated with this person.
     */
    public function residency()
    {
        return $this->belongsTo('App\Residency');
    }

    /**
     * Creates and returns a string with the last name and the name of this person.
     */
    public function fullName() 
    {
        return $this->last_name . ', ' . $this->name;
    }

    public function vehicles()
    {
        return $this->belongsToMany('\App\Vehicle', 'person_vehicle')->using('\App\PersonVehicle');
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
     * Given an array, sets the contact json of this person.
     */
    public function setContact(Array $data)
    {
        $this->contact = json_encode([
            'fax'          => $data['fax']          ?? null,
            'email'        => $data['email']        ?? null,
            'home_phone'   => $data['home_phone']   ?? null,
            'mobile_phone' => $data['mobile_phone'] ?? null
        ]);
    }

    /**
     * Decodes and returns the json where the contact information of this person is stored.
     */
    public function contactToObject()
    {
        return json_decode($this->contact);
    }

    /**
     * Returns an array (that can be encoded to a json) that contains the data needed to be shown on the views of the app.
     */
    public function toShowArray()
    {
        $contact = $this->contactToObject();
        $residency = Residency::find($this->residency_id);
        $company = $this->company();
        $person_company = $this->workingInformation();
        $activity = Activity::find($person_company->activity_id)->name;
        $vehicles = [];
        
        return [
            'personal_information' => [
                'full_name' => $this->fullName(),
                'document_type' => $this->documentTypeToString(),
                'document_number' => $this->document_number,
                'cuil' => $this->cuil,
                'birthday' => $this->birthday ? date('d-m-Y', strtotime($this->birthday)) : '-',
                'sex' => $this->sexToString() ?? '-',
                'blood_type' => $this->blood_type ?? '-',
                'pna' => $this->pna ?? '-',
                'email' => $contact->email ?? '-',
                'phone' => $contact->home_phone ?? '-',
                'mobile_phone' => $contact->mobile_phone ?? '-',
                'fax' => $contact->fax ?? '-',
                'address' => $residency->street ?? '-',
                'apartment' => $residency->apartment ?? '-',
                'cp' => $residency->cp ?? '-',
                'city' => $residency->city ?? '-',
                'province' => $residency->province ?? '-',
                'country' => $residency->country ?? '-',
                'picture_path' => 'storage/documentation/'.$this->last_name[0].'/'.$this->id.'_'.$this->last_name.'_'.$this->name.'/pictures/'.$this->picture_name,
            ],
            'working_information' => [
                'company_url' => route('companies.show', $company->id),
                'company_name' => $company->name,
                'company_area' => $company->area,
                'company_cuit' => $company->cuit,
                'activity' => $activity,
                'art_company' => $person_company->art,
                'pbip' => $person_company->pbip ? date('d-m-Y', strtotime($person_company->pbip)) : '-',
            ],
            'vehicles' => $this->vehicles->toArray(),
            'active_card' => $this->getActiveCard(),
            'inactive_cards' => $this->getInactiveCards(),
            
            //Index info
            'index' => [
                'id' => $this->id,
                'last_name' => $this->last_name,
                'name' => $this->name,
                'cuil' => $this->cuil,
                'company_name' => $this->company()->name,
                'show_url' => route('people.show', $this->id),
            ],
            //Routes
            'edit_url' => route('people.edit', $this->id)

        ];
    }
}
