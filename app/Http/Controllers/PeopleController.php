<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Traits\SaveCardTrait;

use App\Company;
use App\Person;
use App\Card;
use App\Http\Requests\CreatePersonRequest;

class PeopleController extends Controller
{
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
        $companies = Company::orderBy('name','asc')->get();
        $companies_data = [];
        foreach($companies as $company) {
            array_push($companies_data, ['value' => $company->id, 'text' => $company->name]);
        }
        return view('people.create')->with(['companies_data' => $companies_data]);
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
        $person->birthday = $request->birthday;
        $person->company_id = $request->company_id;
        $person->save();
        // Creates the first card associated to this person
        $this->saveCard($person->id);
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
