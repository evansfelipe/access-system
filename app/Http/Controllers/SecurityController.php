<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{ Card, Person, PersonCompany };

class SecurityController extends Controller
{
    public function personInfo($card_number)
    {
        $card = Card::where('number', $card_number)->firstOrFail();
        $person = $card->job->person;
        $company = $card->job->company;
        $vehicles = $person->vehicles->map(function($vehicle) {
            return [
                'id'                => $vehicle->id,
                'type'              => $vehicle->vehicleType->type,
                'allows_container'  => $vehicle->vehicleType->allows_container,
                'plate'             => $vehicle->plate,
                'brand'             => $vehicle->brand,
                'model'             => $vehicle->model,
                'colour'            => $vehicle->colour,
                'year'              => $vehicle->year,
            ];
        });

        
        $response = [
            'id'                => $person->id,
            'full_name'         => $person->fullName(),
            'risk'              => 'Nivel ' . $person->risk,
            'document_type'     => $person->documentTypeToString(),
            'document_number'   => $person->document_number,
            'picture_path'      => $person->getCurrentPicture(),
            'card'              => $card->toArray(),
            'company'           => $company ? $company->name : null,
            'activity'          => $card->job->activity->name,
            'subactivities'     => json_decode($card->job->subactivities),
            'vehicles'          => $vehicles,
            'observations'      => $person->observations->toArray()
        ];

        return response(json_encode($response))->header('Content-Type', 'application/json');
    }
}
