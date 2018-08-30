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
                'id'        => $vehicle->id,
                'plate'     => $vehicle->plate,
                'brand'     => $vehicle->brand,
                'model'     => $vehicle->model,
                'type'      => $vehicle->type,
                'colour'    => $vehicle->colour,
                'year'      => $vehicle->year
            ];
        });

        
        $response = [
            'full_name'         => $person->fullName(),
            'risk'              => 'Nivel ' . $person->risk,
            'document_type'     => $person->documentTypeToString(),
            'document_number'   => $person->document_number,
            'picture_path'      => $person->getCurrentPicturePath(),
            'card'              => $card->toArray(),
            'company'           => $company ? $company->name : null,
            'activity'          => $card->job->activity->name,
            'subactivities'     => json_decode($card->job->subactivities),
            'vehicles'          => $vehicles,
            'observations'      => [
                [
                    'user' => "Gabriel",
                    'text' => "Observación de prueba"
                ],
                [
                    'user' => "Bruno",
                    'text' => "Lorem ipsum dolor, sit amet consectetur adipisicing elit. Harum placeat qui repellat, nostrum repudiandae perspiciatis enim voluptatibus unde et exercitationem quis hic. Possimus dicta eos earum, iste voluptatum cupiditate error!"
                ],
            ]
        ];

        return response(json_encode($response))->header('Content-Type', 'application/json');
    }
}
