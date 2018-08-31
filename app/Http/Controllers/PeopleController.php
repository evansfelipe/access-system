<?php namespace App\Http\Controllers;
use Storage;
use App\Http\Traits\Helpers;
use App\Http\Requests\{ SavePersonRequest };
use App\{ Person, Vehicle, Residency, Company, Card, Activity, PersonCompany, PersonVehicle, PersonDocument};

class PeopleController extends Controller
{

    /**
     * Returns the timestamp of the last update on people's table.
     * 
     * @return Timestamp
     */
    public function updated_at()
    {
        // Doing this way prevents Laravel to call toArray function, since the result of the query is a Collection.
        $person = Person::select('updated_at')->orderBy('updated_at','desc')->first();
        return $person ? $person->updated_at : null; 
    }

    /**
     * Returns an array with each person from the system, order desc by the creation timestamp.
     * 
     * @return Array<Person>
     */
    public function list()
    {
        $people = Person::select('id', 'last_name', 'name', 'cuil')->orderBy('created_at','desc')->get()
                        ->map(function($person) {
                            return [
                                'id'            => $person->id,
                                'last_name'     => $person->last_name,
                                'name'          => $person->name,
                                'cuil'          => $person->cuil,
                                'companies'     => $person->companies()->select('companies.id')->get()->map(function($job) {
                                                        return $job->id;
                                                    }),
                                'company_name'  => $person->companies()->select('name')->get()->implode('name', ' / ')
                            ];
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
        // Saves each person-company relationship.
        if(isset($request->jobs)) {
            foreach ($request->jobs as $job) {
                $person_company = new PersonCompany();
                $person_company->person_id = $person->id;
                $person_company->company_id = $job['company_id'];
                $person_company->activity_id = $job['activity_id'];
                $person_company->subactivities = json_encode($job['subactivities']);
                $person_company->art_company = $job['art_company'];
                $person_company->art_number = $job['art_number'];
                $person_company->save();
                foreach ($job['cards'] as $c) {
                    $card = new Card();
                    $card->person_company_id = $person_company->id;
                    $card->number = $c['number'];
                    $card->from = $c['from'];
                    $card->until = $c['until'];
                    $card->save();
                }
            }
        }
        // Saves each person-vehicle relationship.
        if(isset($request->vehicles_id)) {
           foreach($request->vehicles_id as $vehicle_id) {
               $person_vehicle = new PersonVehicle(['person_id' => $person->id, 'vehicle_id' => $vehicle_id]);
               $person_vehicle->save();
           } 
        }
        $files = $request->file();
        $path = $person->getStorageFolder(true);
        // Saves the picture
        $person->picture_name = Helpers::storeFile($path.'/pictures', $files['picture']);
        $person->save();
        unset($files['picture']);
        // Saves the documentation
        foreach ($files as $key => $file) {
            $person_document = new PersonDocument();
            $person_document->person_id = $person->id;
            $person_document->document_type = $person_document->getConst($key);
            $person_document->document_name = Helpers::storeFile($path, $file);
            $person_document->expiration = $request->{$key.'_expiration'};
            $person_document->save();
        }
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
        return response($person->toJson(), 200)->header('Content-Type', 'application/json');
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
        foreach($person->vehicles as $vehicle) { array_push($vehicles_id, $vehicle->id); }
        $contact = $person->contactToObject();

        $jobs = $person->jobs()->map(function($job, $key) {
            return [
                'key'           =>  $key + 1, // We want the first key to be 1 and not 0 (0 may represent empty in some functions).
                'company_id'    =>  $job->company_id,
                'activity_id'   =>  $job->activity_id,
                'subactivities' =>  $job->subactivities,
                'cards'         =>  $job->cards->map(function($card, $key) {
                                        return [
                                            'key'    => $key + 1, // We want the first key to be 1 and not 0 (0 may represent empty in some functions).
                                            'number' => $card->number,
                                            'from'   => $card->from  ? date('Y-m-d', strtotime($card->from))  : '',
                                            'until'  => $card->until ? date('Y-m-d', strtotime($card->until)) : '',
                                        ]; 
                                    })
            ];
        });

        $data = [
            'id'     => $person->id,
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
                    'picture_path'      => $person->getCurrentPicturePath(),
                ],
                'working_information'   => [
                    // Basic information
                    'risk'              => $person->risk,
                    'art'               => $person->art,
                    'pbip'              => $person->pbip ? date('Y-m-d', strtotime($person->pbip)) : '',
                    // Jobs array
                    'jobs'              => $jobs
                ],
                'assign_vehicles'       => ['vehicles_id' => $vehicles_id],
                'documentation'         => []
            ],
        ];

        return response(json_encode($data))->header('Content-Type', 'application/json');
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
