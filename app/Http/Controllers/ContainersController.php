<?php

namespace App\Http\Controllers;

use App\{ Container, VehicleContainer };

use Illuminate\Http\Request;
use App\Http\Requests\SaveContainerRequest;

class ContainersController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Request\SaveContainerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveContainerRequest $request)
    {
        $container = new Container($request->toArray());
        $container->save();

        if(isset($request->trucks_id)) {
            foreach($request->trucks_id as $truck_id) {
                $vehicle_container = new VehicleContainer(['container_id' => $container->id, 'vehicle_id' => $truck_id]);
                $vehicle_container->save();
            } 
        }

        return response(json_encode(['id' => $container->id]), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Container  $continer
     * @return \Illuminate\Http\Response
     */
    public function show(Container $container)
    {
        return response(json_encode($container->toShowArray()), 200)->header('Content-Type', 'application/json');        
    }

    /**
     * Sends the information for editing the specified resource.
     *
     * @param  \App\Container  $continer
     * @return \Illuminate\Http\Response
     */
    public function edit(Container $container)
    {
        $general_information = $container->toShowArray();

        $trucks_id = [];
        foreach ($general_information['assigned_trucks'] as $assigned_truck) {
            array_push($trucks_id, $assigned_truck['id']);
        }
        $assign_trucks = [
            'trucks_id' => $trucks_id
        ];

        unset($general_information['assigned_trucks']);

        $data = [
            'id'    => $container->id,
            'values' => [
                'general_information'   => $general_information,
                'assign_trucks'         => $assign_trucks
            ]
        ];
        return response(json_encode($data), 200)->header('Content-Type', 'application/json');        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Request\SaveContainerRequest  $request
     * @param  \App\Container  $container
     * @return \Illuminate\Http\Response
     */
    public function update(SaveContainerRequest $request, Container $container)
    {
        $container->fill($request->toArray());
        $container->save();

        $container_vehicles = [];
        foreach($container->vehicles as $vehicle) { array_push($container_vehicles, $vehicle->id); }
        if(isset($request->trucks_id)) {
            $new_trucks = array_diff($request->trucks_id, $container_vehicles);
            $removed_trucks = array_diff($container_vehicles, $request->trucks_id);
            foreach($new_trucks as $vehicle_id){
                $container_vehicle = new VehicleContainer(['vehicle_id' => $vehicle_id, 'container_id' => $container->id]);
                $container_vehicle->save();
            }
            foreach($removed_trucks as $vehicle_id) {
                $container->vehicles()->detach($vehicle_id);
            }
        }

        return response(json_encode(['id' => $container->id]), 200)->header('Content-Type', 'application/json');
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
