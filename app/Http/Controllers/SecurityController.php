<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{ Card, Person, PersonCompany, Vehicle, Entrance };
use Auth;

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

        return response(json_encode($response), 200)->header('Content-Type', 'application/json');
    }
    
    public function storeEntrance(Request $request) {
        \Debugbar::info($request->toArray());
        $entrance = new Entrance();
        $entrance->user_id = Auth::user()->id;

        $card = Card::where('number', $request->card)->firstOrFail();
        $person = $card->job->person;
        $company = $card->job->company;
        $vehicle = Vehicle::find($request->vehicle);

        $entrance->card_number = $card->number;

        $entrance->description = 'Acceso de ' . $person->fullName() . ' con ' . $person->documentTypeToString() . ' ' . $person->document_number . ', ' . 
                                ($company ? 'de la empresa ' . $company->name : '') . ' con la tarjeta ' . $card->number . ',' .
                                ' ha tenido acceso ' . ($request->access ? 'PERMITIDO' : 'DENEGADO') .
                                ($request->action != $request->access ? ' mientras el sistema había indicado que tenía acceso ' . ($request->action ? 'PERMITIDO' : 'DENEGADO') : '') . '. ' .
                                (isset($request->observation) ? 'Observación: ' . $request->observation : '');
        
        if($vehicle) {
            $entrance->vehicle_description = 'Tipo: ' . $vehicle->vehicleType->type . '. Patente: ' . $vehicle->plate . '. ' .
                                            'Marca: ' . $vehicle->brand . '. Modelo: ' . $vehicle->model . '. Año: '. $vehicle->year;
        }

        if($request->container) {
            $entrance->container_description = 'Número de serie: ' . $request->container['serial_number'] . '. Marca: ' . $request->container['brand'] . '. Modelo: ' . $request->container['model'] . '. ' .
            'Formato: ' . $request->container['format'] . '. Tamaño: ' . $request->container['size'] . '. Color: ' . $request->container['colour'] . '. ' .
            'Observaciones: ' . $request->container['observation'];
        }

        $entrance->save();
        \Debugbar::info($entrance);

        return response(200)->header('Content-Type', 'application/json');        
    }
}
