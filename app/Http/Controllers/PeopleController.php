<?php

namespace App\Http\Controllers;
// Models
use App\Person;
use App\Residency;
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

    /**
     * Given a Person and a Request, stores the new data sent on the request on the person attributes.
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Person $person
     */
    private function setPerson(Person $person, Request $request)
    {
        // Assigns the basic person data
        $person->last_name = $request->last_name;
        $person->name = $request->name;
        $person->document_type = $request->document_type;
        $person->document_number = $request->document_number;
        $person->cuil = $request->cuil;
        $person->birthday = $request->birthday;
        $person->sex = $request->sex;
        $person->blood_type = $request->blood_type;
        // If there is a picture, handles its storing
        if($request->hasFile('picture')) {
            // If we are updating a person, and the person has a picture name assigned, that means there already is a picture
            // of this user loaded on the system, so we need to delete it before storing the new one.
            if(!empty($person->picture_name)) {
                unlink('pictures/'.$person->picture_name);
            }
            // Gets the uploaded picture and his extension
            $file = $request->file('picture');
            $extension = $file->getClientOriginalExtension();
            // Creates the filename of the image using the unique cuil of the user and the extension of the uploaded file.
            $filename = $request->cuil . '.' . $extension;
            // Saves the image
            $file->move('pictures/', $filename);
            // Assigns the filename to the person data
            $person->picture_name = $filename;
        }
        $person->pna = $request->pna;
        $person->contact = json_encode([
            'fax' => $request->fax,
            'mail' => $request->email,
            'home_phone' => $request->home_phone,
            'mobile_phone' => $request->mobile_phone
        ]);
    }

    private function setResidency(Residency $residency, int $person_id, Request $request)
    {
        // Assigns the residency of a person
        $residency->person_id = $person_id;
        $residency->street = $request->street;
        $residency->apartment = $request->apartment;
        $residency->cp = $request->cp;
        $residency->country = $request->country;
        $residency->province = $request->province;
        $residency->city = $request->city;
    }

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
        return view('people.index')->with('people', Person::orderBy('created_at','desc')->get()->toJson());
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
     * @param  \App\Http\Requests\People\CreatePersonRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePersonRequest $request)
    {
        // Creates and stores the new person with the given data
        $person = new Person();
        $this->setPerson($person, $request);
        $person->save();
        // Creates and stores the new person's residency with the given data
        $residency = new Residency();
        $this->setResidency($residency, $person->id, $request);
        $residency->save();
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
        return view('people.show')->with('person', $person);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function edit(Person $person)
    {
        return view('people.edit')->with('person', $person);
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
        // If the cuil has changed, we need to rename the name of the picture stored on the server.
        // Done before set the new data because we need to use the old cuil.
        if(($request->cuil != $person->cuil) && !empty($person->picture_name)) {
            // As the picture name has the format {cuil.extension}, dividing the name in the dot and accessing the component number one we get the extension 
            $extension = explode('.', $person->picture_name)[1];
            // Creates the new file name with the new cuil and the current file extension
            $filename = $request->cuil . '.' . $extension;
            // Renames the file on disk
            rename('pictures/'.$person->picture_name, 'pictures/'.$filename);
            // Updates the picture name attribute of the person
            $person->picture_name = $filename;
        }
        // Updates and stores the given person with the given data.
        $this->setPerson($person, $request);
        $person->save();
        // Redirection
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
