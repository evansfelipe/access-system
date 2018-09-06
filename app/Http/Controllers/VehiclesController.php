<?php

namespace App\Http\Controllers;

use App\{ Vehicle, PersonVehicle };
use Illuminate\Http\Request;
use App\Http\Requests\{ SaveVehicleRequest };


class VehiclesController extends Controller
{

    public function updated_at()
    {
        $vehicle = Vehicle::select(['updated_at'])->orderBy('updated_at','desc')->first();
        return $vehicle? $vehicle->updated_at : null;        
    }

    public function list()
    {
        $vehicles = Vehicle::select(['id','plate','brand','model','year','colour','company_id'])->orderBy('created_at','desc')->with('company:id,name')->get()->map(function($vehicle) {
            $vehicle->company_name = $vehicle->company->name;
            unset($vehicle->company);
            return $vehicle;
        });
        return response(json_encode($vehicles))->header('Content-Type', 'application/json');        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function edit(Vehicle $vehicle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        //
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
