<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    protected $fillable = ['series_number', 'format', 'size', 'brand', 'model', 'colour'];

    public const LENGTHS = [
        'series_number' => ['min' => 3, 'max' => 20],
        'format' => ['max' => 50],
        'brand' => ['max' => 20],
        'model' => ['max' => 30],
        'size' => ['max' => 50],
        'colour' => ['max' => 15]
    ];

    /**
     * Creates and returns an array with the validation rules for each attribute that can be
     * entered through an input (or other html component) by the user 
     */
    public static function getValidationRules()
    {
        return [
            'series_number' => [
                'required',
                'string',
                'min:'.Container::LENGTHS['series_number']['min'],
                'max:'.Container::LENGTHS['series_number']['max']
            ],
            'format' => [
                'nullable',
                'string',
                'max:'.Container::LENGTHS['format']['max']
            ],
            'size' => [
                'nullable',
                'string',
                'max:'.Container::LENGTHS['size']['max']
            ],
            'brand' => [
                'nullable',
                'string',
                'max:'.Container::LENGTHS['brand']['max']
            ],
            'model' => [
                'nullable',
                'string',
                'max:'.Container::LENGTHS['model']['max']
            ],
            'colour' => [
                'nullable',
                'string',
                'max:'.Container::LENGTHS['colour']['max']
            ]
        ];;
    }

    /**
     * All vehicles to which this container is associated.
     */
    public function vehicles()
    {
        return $this->belongsToMany('App\Vehicle', 'vehicle_containers')->using('App\VehicleContainer');
    }

    public function toShowArray()
    {
        return [
            'id'                => $this->id,
            'series_number'     => $this->series_number,
            'format'            => $this->format,
            'size'              => $this->size,
            'brand'             => $this->brand,
            'model'             => $this->model,
            'colour'            => $this->colour,
            'assigned_trucks'   => $this->vehicles()->select('vehicles.id','plate','brand','model','company_id')->get()
                                        ->map(function($vehicle) {
                                            return [
                                                'id'            => $vehicle->id,
                                                'plate'         => $vehicle->plate,
                                                'brand'         => $vehicle->brand,
                                                'model'         => $vehicle->model,
                                                'company_name'  => $vehicle->company->name
                                            ];
                                        })
        ];
    }
}
