<?php namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Auth;
use App\Http\Traits\Helpers;
use Illuminate\Http\Request;
use App\Http\Requests\{ SavePersonRequest };
use App\{ Person, Vehicle, Residency, Company, Card, Activity, PersonCompany, PersonVehicle, PersonDocument, Observation};
use PDF;

class PeopleController extends Controller
{

    /**
     * Returns an array with the routes to each picture associated with a given person.
     * 
     * @return Array<String>
     */
    public function pictures(Person $person)
    {
        $images = collect(Storage::files($person->getStorageFolder().'pictures/'))->map(function($file) {
            return Helpers::getImageAsDataURI($file);
        });
        return response(json_encode($images))->header('Content-Type', 'application/json');
    }

    /**
     * Returns something
     * 
     * @param  \App\PersonDocument  $person_document
     */
    public function document(PersonDocument $person_document)
    {
        $path = Person::findOrFail($person_document->person_id)->getStorageFolder() . $person_document->document_name;
        $document = Storage::get($path);
        $mime  = Storage::mimeType($path);
        return response($document)->header('Content-Type', $mime);
    }

    public function pdf(Person $person)
    {
        // return view('pdfs.person', $person->toPdfArray());

        $pdf = PDF::loadView('pdfs.person', $person->toPdfArray());
        return $pdf->download('resumen.pdf');
    }

    /**
     * Stores a new observation and returns it
     */
    public function newObservation(Request $request, $person_id)
    {
        $observation = new Observation($request->toArray());
        $observation->person_id = intval($person_id);
        $observation->user_id = Auth::user()->id;
        $observation->save();
        return response(json_encode($observation))->header('Content-Type', 'application/json');        
    }

    /**
     * Stores the document asociated to a specified person
     */
    public function storeDocument($person_id, $request, $files, $file_type, $file_name, $path)
    {
        if(isset($files[$file_name])) {
            $person_document = new PersonDocument();
            $person_document->person_id = $person_id;
            $person_document->document_type = $person_document->getConst($file_type);
            $person_document->document_name = Helpers::storeFile($path, $files[$file_name]);
            $person_document->expiration = $request->{$file_name.'_expiration'};
            $person_document->required = $request->{$file_name.'_required'} === 'true';
            $person_document->save();
        }
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
        Helpers::storeLocation($residency->city, $residency->province, $residency->country);
        // Saves the person
        $person = new Person($request->toArray());
        $person->setContact($request->toArray());
        $person->residency_id = $residency->id;
        $person->save();
        // Set files array
        $files = $request->file();
        $path = $person->getStorageFolder();
        // Saves the picture
        $person->picture_name = Helpers::storeFile($path.'/pictures', $files['picture']);
        $person->save();
        unset($files['picture']);
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

                $file_name = $job['key'].'-company_note';
                $this->storeDocument($person->id, $request, $files, 'company_note', $file_name, $path);
                unset($files[$file_name]);

                $file_name = $job['key'].'-art_file';
                $this->storeDocument($person->id, $request, $files, 'art_file', $file_name, $path);
                unset($files[$file_name]);
            }
        }
        // Saves each person-vehicle relationship.
        if(isset($request->vehicles_id)) {
           foreach($request->vehicles_id as $vehicle_id) {
               $person_vehicle = new PersonVehicle(['person_id' => $person->id, 'vehicle_id' => $vehicle_id]);
               $person_vehicle->save();
           } 
        }
        // Saves the documentation
        foreach ($files as $key => $file) {
            $this->storeDocument($person->id, $request, $files, $key, $key, $path);
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
        return response(json_encode($person->toShowArray()), 200)->header('Content-Type', 'application/json');
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
                    'picture_path'      => $person->getCurrentPicturePath(), // Change this 
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
