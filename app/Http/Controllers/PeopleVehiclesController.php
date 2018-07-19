<?php

namespace App\Http\Controllers;

use App\{ Vehicle, PersonVehicle };
use Illuminate\Http\Request;

class PeopleVehiclesController extends Controller
{
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
     * Display the specified resource.
     *
     * @param  \App\PersonVehicle  $personVehicle
     * @return \Illuminate\Http\Response
     */
    public function show(PersonVehicle $personVehicle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PersonVehicle  $personVehicle
     * @return \Illuminate\Http\Response
     */
    public function edit(PersonVehicle $personVehicle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PersonVehicle  $personVehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PersonVehicle $personVehicle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PersonVehicle  $personVehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy(PersonVehicle $personVehicle)
    {
        //
    }
}
