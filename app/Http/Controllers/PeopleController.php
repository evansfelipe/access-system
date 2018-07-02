<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Person;
use App\Card;
use App\Http\Requests\CreatePersonRequest;

class PeopleController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('people.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreatePersonRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePersonRequest $request)
    {
        // Creates the new person with the given data
        $person = new Person();
        $person->cuil = $request->cuil;
        $person->last_name = $request->last_name;
        $person->name = $request->name;
        $person->sex = $request->sex;
        $person->save();
        // Creates the first card associated to this person
        $card = new Card();
        $card->active = true;
        $card->person_id = $person->id;
        $card->save();
        // Redirection
        return redirect()->route('people.show', $person->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $person = Person::find($id);
        return view('people.show')->withPerson($person);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
