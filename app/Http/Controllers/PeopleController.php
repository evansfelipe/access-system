<?php

namespace App\Http\Controllers;
// Models
use App\Person;
use App\Company;
use App\Card;
// Requests
use Illuminate\Http\Request;
use App\Http\Requests\CreatePersonRequest;
// Traits
use App\Http\Traits\SaveCardTrait;

class PeopleController extends Controller
{
    // As we are going to save cards associated with one or more persons, we need to use the correspondent Trait.
    use SaveCardTrait;

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
        // Gets all companies loaded on the system
        $companies = Company::orderBy('name','asc')->get();
        // Creates an empty array to send the companies data to the view
        $companies_data = [];
        // Adds each company to the data array as a key => value array
        foreach($companies as $company) {
            array_push($companies_data, [
                'value' => $company->id, // Select's option (html) value that the form will submit
                'text' => $company->name // Select's option (html) text that the user will see
            ]);
        }
        // Returns the people's creation view with the correspondent companies data 
        return view('people.create', ['companies_data' => $companies_data]);
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
        $person->last_name = $request->last_name;
        $person->name = $request->name;
        $person->cuil = $request->cuil;
        $person->sex = $request->sex;
        $person->company_id = $request->company_id;
        $person->birthday = $request->birthday;
        $person->save();
        // Creates the first card associated to this person
        $this->saveCard($person->id);
        // Redirection
        return redirect()->route('people.show', $person->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function show(Person $person)
    {
        return view('people.show')->withPerson($person);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function edit(Person $person)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Person $person)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function destroy(Person $person)
    {
        //
    }
}
