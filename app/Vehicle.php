<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\Helpers;

class Vehicle extends Model
{
    protected $fillable = ['company_id', 'type', 'owner', 'plate', 'brand', 'model', 'year', 'colour', 'insurance', 'vtv'];

    public const LENGTHS = [
        'type' => ['max' => 15],
        'owner' => ['max' => 100],
        'plate' => ['min' => 5 ,'max' => 10],
        'brand' => ['max' => 20],
        'model' => ['max' => 30],
        'colour' => ['max' => 15]
    ];

    /**
     * Creates and returns an array with the validation rules for each attribute that can be
     * entered through an input (or other html component) by the user 
     */
    public static function getValidationRules()
    {
        return [
            'company_id' => [
                'required',
                'exists:companies,id'
            ],
            'type' => [
                'required',
                'string',
                'max:'.Vehicle::LENGTHS['type']['max']
            ],
            'owner' => [
                'nullable',
                'string',
                'max:'.Vehicle::LENGTHS['owner']['max']
            ],
            'plate' => [
                'required',
                'unique:vehicles',
                'string',
                'min:'.Vehicle::LENGTHS['plate']['min'],
                'max:'.Vehicle::LENGTHS['plate']['max']
            ],
            'brand' => [
                'required',
                'string',
                'max:'.Vehicle::LENGTHS['brand']['max']
            ],
            'model' => [
                'required',
                'string',
                'max:'.Vehicle::LENGTHS['model']['max']
            ],
            'colour' => [
                'nullable',
                'string',
                'max:'.Vehicle::LENGTHS['colour']['max']
            ],
            'year' => [
                'nullable',
                'integer',
                'digits:4',
                'min:1900',
                'max:'.(date('Y')+1),
            ],
            'insurance' => [
                'nullable',
                'date',
                "regex:/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", 
                'after:'.date('Y-m-d'), 
            ],
            'vtv' => [
                'nullable',
                'date',
                "regex:/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", 
                'after:'.date('Y-m-d'), 
            ]
        ];;
    }

    public function people()
    {
        return $this->belongsToMany('\App\Person', 'person_vehicle')->using('\App\PersonVehicle');
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function toShowArray()
    {
        return [
            'id'                => $this->id,
            'company'           => [
                'id'    => $this->company->id,
                'name'  => $this->company->name
            ],
            'plate'             => $this->plate,
            'owner'             => $this->owner,
            'type'              => $this->type,
            'brand'             => $this->brand,
            'model'             => $this->model,
            'year'              => $this->year,
            'colour'            => $this->colour,
            'insurance'         => Helpers::timestampToDate($this->insurance),
            'vtv'               => Helpers::timestampToDate($this->vtv),
            'assigned_people'   => $this->people()->select('people.id','last_name','name','cuil')->get()
                                        ->map(function($person) {
                                            return [
                                                'id'            => $person->id,
                                                'full_name'     => $person->fullName(),
                                                'cuil'          => $person->cuil,
                                                'company_name'  => $person->companies()->select('name')->get()->implode('name', ' / ')
                                            ];
                                        })
        ];
    }
}
