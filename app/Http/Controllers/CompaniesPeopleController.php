<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\{Company, Person};

class CompaniesPeopleController extends Controller
{

    /**
     * Creates a relational array with each company, where the key of each component is a company's id, and the value
     * is the company's name. It's used to display the basic information about the stored companies on the system at some blade view.
     * 
     * @return Array
     */
    private function companiesDataToKeyValue()
    {
        // Gets all the companies of the system
        $companies = Company::orderBy('name','asc')->get();
        // Creates an empty array to send the companies data to the view
        $companies_data = [];
        // Adds each company to the data array as a key => value array
        foreach($companies as $company) {
            array_push($companies_data, [
                'value' => $company->id,
                'text' => $company->name
            ]);
        }
        return $companies_data;
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
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function create(Person $person)
    {
        return view('people.companies.create')->with('companies_data', $this->companiesDataToKeyValue())
                                              ->with('person_id', $person->id)
                                              ->with('person_name', $person->fullName());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
