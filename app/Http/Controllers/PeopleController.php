<?php namespace App\Http\Controllers;

use App\Http\Requests\{ SavePersonRequest };
use App\{ Person, Vehicle, Residency, Company, Card, Activity, PersonCompany, PersonVehicle };

class PeopleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $people = Person::orderBy('created_at','desc')->get()->toArray();
        foreach($people as $key => $value){ $people[$key] = $value['index']; }
        return response(json_encode($people))->header('Content-Type', 'application/json');


        return view('people.index')->with('people', json_encode($people));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $vehicles = Vehicle::all()->map(function ($vehicle) {
            $vehicle['picked'] = false;
            return $vehicle;
        });

        return response(
            json_encode([
                'person' => json_encode(null),
                'vehicles' => json_encode($vehicles),
                'companies' => Company::all(['id','name'])->toJson(),
                'activities' => Activity::all(['id','name'])->toJson()
            ])
        )->header('Content-Type', 'application/json');


        return view('person-creation.index')->with('vehicles', $vehicles)
                                            ->with('companies', Company::all(['id','name'])->toJson())
                                            ->with('activities', Activity::all(['id','name'])->toJson());
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
               $person_vehicle = new PersonVehicle(['person_id' => $person->id, 'vehicle_id' => $vehicle_id]);
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
    public function show(Person $person)
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
        $person_vehicles = [];
        foreach($person->vehicles as $vehicle) { array_push($person_vehicles, $vehicle->id); }
        $vehicles = Vehicle::all()->map(function ($vehicle) use($person_vehicles) {
            $vehicle->picked = in_array($vehicle->id, $person_vehicles);
            return $vehicle;
        });

        $card = $person->getActiveCard();
        $contact = $person->contactToObject();
        $person_company = $person->workingInformation();
        // Generates the json with the actual information of the person to send to the view.
        $person_json = json_encode([
            'update_url' => route('people.update', $person->id),
            'personal_information'  => json_encode([
                // Basic information
                'last_name'         => $person->last_name,
                'name'              => $person->name,
                'document_type'     => $person->document_type,
                'document_number'   => $person->document_number,
                'cuil'              => $person->cuil,
                'birthday'          => date('Y-m-d', strtotime($person->birthday)),
                'sex'               => $person->sex,
                'blood_type'        => $person->blood_type,
                'pna'               => $person->pna,
                // Contact
                'email'             => $contact->email,
                'home_phone'        => $contact->home_phone,
                'mobile_phone'      => $contact->mobile_phone,
                'fax'               => $contact->fax,
                // Residency
                'street'            => $person->residency->street,
                'apartment'         => $person->residency->apartment,
                'cp'                => $person->residency->cp,
                'country'           => $person->residency->country,
                'province'          => $person->residency->province,
                'city'              => $person->residency->city
            ]),
            'working_information'   => json_encode([
                'company_id'        => $person->company()->id,
                'activity_id'       => $person_company->activity_id,
                'art'               => $person_company->art,
                'pbip'              => date('Y-m-d', strtotime($person_company->pbip))
            ]),
            'assign_vehicles'   => json_encode([

            ]),
            'first_card'    => json_encode([
                'number'    => $card->number,
                'risk'      => $card->risk,
                'from'      => date('Y-m-d', strtotime($card->from)),
                'until'     => date('Y-m-d', strtotime($card->until))
            ]),
            'documentation' => json_encode([

            ]),
        ]);
        return view('people.edit')  ->with('vehicles', $vehicles)
                                    ->with('person', $person_json)
                                    ->with('companies', Company::all(['id','name'])->toJson())
                                    ->with('activities', Activity::all(['id','name'])->toJson());
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
        // Updates the person
        $person->fill($request->toArray());
        $person->setContact($request->toArray());
        $person->save();
        // Updates the residency
        $person->residency->fill($request->toArray());
        $person->residency->save();
        // Updates the person-company relationship
        $person_company = $person->company()->pivot;
        $person_company->activity_id = $request->activity_id;
        $person_company->art = $request->art;
        $person_company->pbip = $request->pbip;
        $person_company->save();
        // Updates the person-vehicles relationships
        $person_vehicles = [];
        foreach($person->vehicles as $vehicle) { array_push($person_vehicles, $vehicle->id); }
        if(isset($request->vehicles_id)) {
            $new_vehicles = array_diff($request->vehicles_id, $person_vehicles);
            $removed_vehicles = array_diff($person_vehicles, $request->vehicles_id);
            foreach($new_vehicles as $vehicle_id){
                $person_vehicle = new PersonVehicle(['vehicle_id' => $vehicle_id, 'person_id' => $person->id]);
                $person_vehicle->save();
            }
            foreach($removed_vehicles as $vehicle_id) {
                $person->vehicles()->detach($vehicle_id);
            }
        }

        $card = $person->getActiveCard();
        $card->fill($request->toArray());
        $card->save();

        return response(route('people.show', $person->id), 200)->header('Content-Type', 'text/plain');
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
