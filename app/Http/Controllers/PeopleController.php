<?php

namespace App\Http\Controllers;
// Models
use App\{ Person, Vehicle, Residency, Company, Card, Activity, PersonCompany, PersonVehicle };
// Requests
use Illuminate\Http\Request;
use App\Http\Requests\SavePersonRequest;
// Traits
use App\Http\Traits\{ SaveResidencyTrait };

class PeopleController extends Controller
{
    use SaveResidencyTrait;
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
            'email' => $request->email,
            'home_phone' => $request->home_phone,
            'mobile_phone' => $request->mobile_phone
        ]);
        $person->residency_id = SaveResidencyTrait::saveResidency($request);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $people = Person::orderBy('created_at','desc')->get()->toJson();
        return view('people.index')->with('people', $people);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vehicles = Vehicle::all();
        foreach($vehicles as $vehicle) { $vehicle->picked = false; }
        return view('person-creation.index')->with('companies', Company::all(['id','name'])->toJson())
                                            ->with('activities', Activity::all(['id','name'])->toJson())
                                            ->with('vehicles', $vehicles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SavePersonRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SavePersonRequest $request)
    {
        // Saves the residency
        $residency = new Residency($request->toArray());
        $residency->save();
        // Saves the person
        $person = new Person($request->toArray());
        $person->setContact($request->toArray());
        $person->residency_id = $residency->id;
        $person->save();
        // Saves the person-company relationship
        $person_company = new PersonCompany($request->toArray());
        $person_company->person_id = $person->id;
        $person_company->save();
        // Saves each person-vehicle relationship.
        if(isset($request->vehicles_id)) {
           foreach($request->vehicles_id as $vehicle_id) {
               $person_vehicle = new PersonVehicle(['vehicle_id' => $vehicle_id]);
               $person_vehicle->person_id = $person->id;
               $person_vehicle->save();
           } 
        }
        // Saves the card
        $card = new Card($request->toArray());
        $card->person_id = $person->id;
        $card->save();
        /**
         * TODO: store the documentation.
         */
        return response(route('people.show', $person->id), 200)->header('Content-Type', 'text/plain');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Person $person)
    {
        $person_info = $person->toArray();
        unset($person_info['index']);
        return view('people.show')->with('person', json_encode($person_info));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function edit(Person $person)
    {
        $contact = json_decode($person->contact);
        $person->email = $contact->email;
        $person->home_phone = $contact->home_phone;
        $person->mobile_phone = $contact->mobile_phone;
        $person->fax = $contact->fax;
        return view('people.edit')->with('person', $person);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\SavePersonRequest  $request
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function update(SavePersonRequest $request, Person $person)
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
