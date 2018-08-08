<?php

namespace App\Http\Controllers;

use App\Vehicle;
use Illuminate\Http\Request;
use App\Http\Requests\{ SaveVehicleRequest };


class VehiclesController extends Controller
{

    public function updated_at()
    {
        return Vehicle::select(['updated_at'])->orderBy('updated_at','desc')->first();        
    }

    public function list()
    {
        $vehicles = Vehicle::all(['id','plate','brand','model','year','colour','company_id']);
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
        $vehicle = new Vehicle($request->toArray());
        $vehicle->save();
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
        //
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
