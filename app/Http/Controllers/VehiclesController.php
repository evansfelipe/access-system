<?php

namespace App\Http\Controllers;

use App\{ Vehicle, PersonVehicle };
use Illuminate\Http\Request;
use App\Http\Requests\{ SaveVehicleRequest };


class VehiclesController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\SaveVehicleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveVehicleRequest $request)
    {
        // Saves the new vehicle
        $vehicle = new Vehicle($request->toArray());
        $vehicle->save();
        // Creates the association between person and vehicle
        if(isset($request->people_id)) {
            foreach($request->people_id as $person_id) {
                $person_vehicle = new PersonVehicle(['person_id' => $person_id, 'vehicle_id' => $vehicle->id]);
                $person_vehicle->save();
            } 
        }
        return response(json_encode(['id' => $vehicle->id]), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function show(Vehicle $vehicle)
    {
        return response(json_encode($vehicle->toShowArray()), 200)->header('Content-Type', 'application/json');        
    }

    /**
     * Sends the information for editing the specified resource.
     *
     * @param  \App\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function edit(Vehicle $vehicle)
    {
        $veh = $vehicle->toShowArray();

        $people_id = [];
        foreach ($veh['assigned_people'] as $assigned_person) {
            array_push($people_id, $assigned_person['id']);
        }
        $assign_people = [
            'people_id' => $people_id
        ];

        $general_information = [
            'company_id'    => $veh['company']['id'],
            'vtv'           => $vehicle->vtv ? date('Y-m-d', strtotime($vehicle->vtv)) : '',
            'insurance'     => $vehicle->insurance ? date('Y-m-d', strtotime($vehicle->insurance)) : '',
        ];

        unset($veh['id'], $veh['company'], $veh['vtv'], $veh['insurance'], $veh['assigned_people']);

        $general_information = array_merge($general_information, $veh);

        $data = [
            'id'    => $vehicle->id,
            'values' => [
                'general_information'   => $general_information,
                'assign_people'         => $assign_people
            ]
        ];
        return response(json_encode($data), 200)->header('Content-Type', 'application/json');        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function update(SaveVehicleRequest $request, Vehicle $vehicle)
    {
        $vehicle->fill($request->toArray());
        $vehicle->save();

        $vehicle_people = [];
        foreach($vehicle->people as $person) { array_push($vehicle_people, $person->id); }
        if(isset($request->people_id)) {
            $new_people = array_diff($request->people_id, $vehicle_people);
            $removed_people = array_diff($vehicle_people, $request->people_id);
            foreach($new_people as $person_id){
                $person_vehicle = new PersonVehicle(['vehicle_id' => $vehicle->id, 'person_id' => $person_id]);
                $person_vehicle->save();
            }
            foreach($removed_people as $person_id) {
                $vehicle->people()->detach($person_id);
            }
        }

        return response(json_encode(['id' => $vehicle->id]), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vehicle $vehicle)
    {
        //
    }
}
