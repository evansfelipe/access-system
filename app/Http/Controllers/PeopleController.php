<?php namespace App\Http\Controllers;

use Storage;
use App\Http\Requests\{ SavePersonRequest };
use App\{ Person, Vehicle, Residency, Company, Card, Activity, PersonCompany, PersonVehicle };

class PeopleController extends Controller
{

    /**
     * Returns the timestamp of the last update on people's table.
     * 
     * @return Timestamp
     */
    public function updated_at()
    {
        return Person::select(['updated_at'])->orderBy('updated_at','desc')->first(); 
    }

    /**
     * Returns an array with each person from the system, order desc by the creation timestamp.
     * 
     * @return Array<Person>
     */
    public function list()
    {
        $people = Person::select(['id','last_name','name','cuil'])->orderBy('created_at','desc')
            ->with('companies:name')->get()->map( function($person) {
                $person->company_name = $person->companies[0]->name;
                unset($person->companies);
                return $person;
            });
        
        return response(json_encode($people))->header('Content-Type', 'application/json');        
    }

    /**
     * Returns an array with the routes to each picture associated with a given person.
     * 
     * @return Array<String>
     */
    public function pictures(Person $person)
    {
        $path = 'storage/documentation/'.$person->last_name[0].'/'.$person->id.'_'.$person->last_name.'_'.$person->name.'/pictures';
        $pictures = [];
        foreach(scandir($path, 1) as $file) {
            if($file !== '.' && $file !== '..') {
                array_push($pictures, $path.'/'.$file);
            }
        }
        return response(json_encode($pictures))->header('Content-Type', 'application/json');
    }

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
        // Saves the picture
        $path = 'public/documentation/'.$person->last_name[0].'/'.$person->id.'_'.$person->last_name.'_'.$person->name;
        $person->picture_name = time() . '.' . $request->file('picture')->guessExtension();
        Storage::putFileAs($path.'/pictures', $request->file('picture'), $person->picture_name);
        $person->save();
        /**
         * TODO: store the documentation.
         */
        return response(json_encode(['id' => $person->id]), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function show(Person $person)
    {
        $person_info = $person->toShowArray();
        unset($person_info['index']);
        return response($person_info, 200)->header('Content-Type', 'application/json');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function edit(Person $person)
    {
        $vehicles_id = [];
        foreach($person->vehicles as $vehicle) { 
            array_push($vehicles_id, $vehicle->id); 
        }
        $card = $person->getActiveCard();
        $contact = $person->contactToObject();
        $person_company = $person->workingInformation();
        // Generates the json with the actual information of the person to send to the view.
        $person_json = [
            'id' => $person->id,
            'values' => [
                'personal_information'  => [
                    // Basic information
                    'last_name'         => $person->last_name,
                    'name'              => $person->name,
                    'document_type'     => $person->document_type,
                    'document_number'   => $person->document_number,
                    'cuil'              => $person->cuil,
                    'birthday'          => $person->birthday ? date('Y-m-d', strtotime($person->birthday)) : '',
                    'sex'               => $person->sex,
                    'blood_type'        => $person->blood_type,
                    'pna'               => $person->pna,
                    // Contact
                    'fax'               => $contact->fax,
                    'email'             => $contact->email,
                    'home_phone'        => $contact->home_phone,
                    'mobile_phone'      => $contact->mobile_phone,
                    // Residency
                    'street'            => $person->residency->street,
                    'apartment'         => $person->residency->apartment,
                    'cp'                => $person->residency->cp,
                    'country'           => $person->residency->country,
                    'province'          => $person->residency->province,
                    'city'              => $person->residency->city,
                    'picture_path'      => 'storage/documentation/'.$person->last_name[0].'/'.$person->id.'_'.$person->last_name.'_'.$person->name.'/pictures/'.$person->picture_name,
                ],
                'working_information'   => [
                    'company_id'        => $person->company()->id,
                    'activity_id'       => $person_company->activity_id,
                    'art'               => $person_company->art,
                    'pbip'              => $person_company->pbip ? date('Y-m-d', strtotime($person_company->pbip)) : '',
                    'jobs'              => []
                ],
                'assign_vehicles'   => [
                    'vehicles_id' => $vehicles_id
                ],
                'first_card'    => [
                    'number'    => $card->number,
                    'risk'      => $card->risk,
                    'from'      => $card->from ? date('Y-m-d', strtotime($card->from)) : '',
                    'until'     => $card->until ? date('Y-m-d', strtotime($card->until)) : ''
                ],
                'documentation' => [
                ]
            ],
        ];

        \Debugbar::info($person_json);

        return response(json_encode($person_json))->header('Content-Type', 'application/json');
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
        if($request->has('picture')) {
            $path = 'public/documentation/'.$person->last_name[0].'/'.$person->id.'_'.$person->last_name.'_'.$person->name;
            $person->picture_name = time() . '.' . $request->file('picture')->guessExtension();
            Storage::putFileAs($path.'/pictures', $request->file('picture'), $person->picture_name);
        }
        $person->save();
        // Updates the residency
        $person->residency->fill($request->toArray());
        $person->residency->save();
        // Updates the person-company relationship
        $person_company = $person->company()->pivot;
        $person_company->company_id = $request->company_id;
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

        return response(json_encode(['id' => $person->id]), 200)->header('Content-Type', 'application/json');
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
