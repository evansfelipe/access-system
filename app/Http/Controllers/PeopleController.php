<?php

namespace App\Http\Controllers;
// Models
use App\Person;
use App\Company;
use App\Card;
// Requests
use Illuminate\Http\Request;
use App\Http\Requests\People\CreatePersonRequest;
use App\Http\Requests\People\UpdatePersonRequest;
// Traits
use App\Http\Traits\SaveCardTrait;

class PeopleController extends Controller
{
    // As we are going to save cards associated with one or more persons, we need to use the correspondent Trait.
    use SaveCardTrait;

    private function setPerson(Person $person, Request $request)
    {
        $person->last_name = $request->last_name;
        $person->name = $request->name;
        $person->cuil = $request->cuil;
        $person->sex = $request->sex;
        $person->company_id = $request->company_id;
        $person->birthday = $request->birthday;

        if($request->hasFile('picture')) {
            if(!empty($person->picture_name)) {
                unlink('pictures/'.$person->picture_name);
            }
            $file = $request->file('picture');
            $extension = $file->getClientOriginalExtension();
            $filename = $request->cuil . '.' . $extension;
            $file->move('pictures/', $filename);
            $person->picture_name = $filename;
        }
    }

    private function companiesDataToKeyValue()
    {
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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('people.create', ['companies_data' => $this->companiesDataToKeyValue()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\People\CreatePersonRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePersonRequest $request)
    {
        // Creates the new person with the given data
        $person = new Person();
        $this->setPerson($person, $request);
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
        return view('people.edit')->withPerson($person)
                                  ->with('companies_data', $this->companiesDataToKeyValue());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\People\UpdatePersonRequest  $request
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePersonRequest $request, Person $person)
    {
        // Done before set the new data because we need to use the old cuil
        if($request->cuil != $person->cuil) {
            $extension = explode(".", $person->picture_name)[1];
            $filename = $request->cuil . '.' . $extension;
            rename("pictures/".$person->picture_name, "pictures/".$filename);
            $person->picture_name = $filename;
        }
        $this->setPerson($person, $request);
        $person->save();
        return redirect()->route('people.show', $person->id);
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
