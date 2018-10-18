<?php namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Auth, DB;
use Illuminate\Http\Request;
use App\Http\Requests\{ SavePersonRequest };
use App\{ Person, Vehicle, Residency, Company, Card, Activity, PersonCompany, PersonVehicle, PersonJobGroup, PersonDocument, Observation};
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
            return \Helpers::getImageAsDataURI($file);
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
        // PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadView('pdfs.person', $person->toPdfArray());
        return $pdf->stream();
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
    public function storeDocument($person_id, $fileDataURI, $expiration, $file_type, $path)
    {
        $ret = null;
        if(isset($fileDataURI)) {
            $person_document = new PersonDocument();
            $person_document->person_id = $person_id;
            $person_document->document_type = $person_document->getConst($file_type);
            $person_document->document_name = \Helpers::storeFile($path, $fileDataURI);
            $person_document->expiration = $expiration;
            $person_document->save();
            $ret = $person_document->id;
        }
        return $ret;
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
        \Helpers::storeLocation($residency->city, $residency->province, $residency->country);
        // Saves the person
        $person = new Person($request->toArray());
        $person->setContact($request->toArray());
        $person->setRequiredDocumentation($request->documents_required);
        $person->residency_id = $residency->id;
        $person->save();
        // Set files array
        $path = $person->getStorageFolder();
        // Saves the picture
        $person->picture_name = \Helpers::storeFile($path.'pictures', $request->picture);
        $person->save();
        // Saves each person-company relationship.
        if(isset($request->jobs)) {
            foreach ($request->jobs as $job) {
                $person_company = new PersonCompany();
                $person_company->person_id = $person->id;
                $person_company->company_id = $job['company_id'];
                $person_company->activity_id = $job['activity_id'];
                $person_company->subactivities = json_encode($job['subactivities']);

                foreach($job['subactivities'] as $name) {
                    \Helpers::storeSubactivity($name, $job['activity_id']);
                }

                $person_company->art_company = $job['art_company'];
                $person_company->art_number = $job['art_number'];

                $person_company->company_note_id = $this->storeDocument($person->id, $job['company_note']['file'], $job['company_note']['expiration'], 'company_note', $path);
                $person_company->art_file_id = $this->storeDocument($person->id, $job['art_file']['file'], $job['art_file']['expiration'], 'art_file', $path);

                $person_company->save();

                foreach ($job['cards'] as $c) {
                    $card = new Card();
                    $card->person_company_id = $person_company->id;
                    $card->number = $c['number'];
                    $card->from = $c['from'];
                    $card->until = $c['until'];
                    $card->save();
                }
                
                foreach($job['groups'] as $group) {
                    $job_group = new PersonJobGroup(['job_id' => $person_company->id, 'group_id' => $group]);
                    $job_group->save();
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
        // Saves the documentation
        foreach ($request->documents as $key => $data) {
            $this->storeDocument($person->id, $data['file'], $data['expiration'], $key, $path);
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
        // foreach($person->vehicles()->select('vehicle.id')->get() as $vehicle) { array_push($vehicles_id, $vehicle->id); }
        $vehicles_id = $person->vehicles->pluck('id');
        $contact = $person->contactToObject();

        $jobs = $person->jobs()->map(function($job) {
            $company_note = $job->company_note;
            $art_file = $job->art_file;
            return [
                'key'            => $job->id,
                'company_id'     => $job->company_id,
                'company_note'   => $company_note === null ? ['name' => null, 'expiration' => null] : [
                    'name'       => $company_note->document_name ? PersonDocument::typeToString($company_note->document_type) : null,
                    'expiration' => $company_note->expiration,
                ],
                'activity_id'    => $job->activity_id,
                'subactivities'  => $job->subactivities,
                'groups'         => $job->groups->pluck('id'),
                'art_company'    => $job->art_company,
                'art_number'     => $job->art_number,
                'art_file'       => $art_file === null ? ['name' => null, 'expiration' => null] : [
                    'name'       => $art_file->document_name ? PersonDocument::typeToString($art_file->document_type) : null,
                    'expiration' => $art_file->expiration,
                ],
                'cards'          => $job->cards->map(function($card) {
                    return [
                        'key'    => $card->id,
                        'number' => $card->number,
                        'from'   => $card->from  ? date('Y-m-d', strtotime($card->from))  : '',
                        'until'  => $card->until ? date('Y-m-d', strtotime($card->until)) : '',
                    ]; 
                })
            ];
        });

        // Gets last expiration of each type
        $person_documents_ids = $person->documents()->select(DB::raw('max(id) as id'))->groupBy('document_type')->get()->pluck('id');
        $person_documents = $person->documents()->select('document_type', 'expiration')->whereIn('id',$person_documents_ids)->get();

        $documents = [];
        $documents_types = PersonDocument::TYPES;
        unset($documents_types['company_note'],$documents_types['art_file']);
        foreach (PersonDocument::TYPES as $key => $value) {
            $i = 0;
            while($i < count($person_documents) && $person_documents[$i]['document_type'] !== $value) {
                $i++;
            }
            if($i < count($person_documents)) {
                $documents[$key]['expiration'] = $person_documents[$i]['expiration'] ? date('Y-m-d', strtotime($person_documents[$i]['expiration'])) : '';
                $documents[$key]['name'] = PersonDocument::typeToString($value);
            }
            else {
                $documents[$key]['expiration'] = null;
                $documents[$key]['name'] = null;
            }
        }
        
        $data = [
            'id'     => $person->id,
            'values' => [
                'personal_information'  => [
                    // Basic information
                    'picture_path'      => $person->getCurrentPicture(),
                    'last_name'         => $person->last_name,
                    'name'              => $person->name,
                    'document_type'     => strval($person->document_type),
                    'document_number'   => $person->document_number,
                    'cuil'              => $person->cuil,
                    'birthday'          => $person->birthday ? date('Y-m-d', strtotime($person->birthday)) : '',
                    'sex'               => $person->sex,
                    'blood_type'        => $person->blood_type,
                    'pna'               => $person->pna,
                    'risk'              => $person->risk,
                    'homeland'          => $person->homeland,
                    'register_number'   => $person->register_number,
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
                    'city'              => $person->residency->city,
                ],
                'working_information'   => [ 'jobs' => $jobs ],
                'assign_vehicles'       => [ 'vehicles_id' => $vehicles_id ],
                'documentation'         => [
                    'documents'          => $documents,
                    'documents_required' => json_decode($person->required_documentation)
                ]
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
        $person->setRequiredDocumentation($request->documents_required);
        $path = $person->getStorageFolder();
        if($request->has('picture')) {
            $person->picture_name = \Helpers::storeFile($path.'pictures', $request->picture);
        }
        $person->save();

        // Updates the residency
        $person->residency->fill($request->toArray());
        $person->residency->save();
        \Helpers::storeLocation($person->residency->city, $person->residency->province, $person->residency->country);

        // Updates the person-company relationship
        $existing_jobs = [];
        if(isset($request->jobs)) {
            foreach ($request->jobs as $job) {

                $old_jobs = $person->jobs()->pluck('id')->toArray();
                $existing_job = PersonCompany::find($job['key']);
                if($existing_job) {
                    $existing_job->fill($job);
                    $existing_job->subactivities = json_encode($job['subactivities']);
                    if(isset($job['company_note']['file'])) {
                        $existing_job->company_note_id = $this->storeDocument($person->id, $job['company_note']['file'], $job['company_note']['expiration'], 'company_note', $path);
                    }
                    if(isset($job['company_note']['file'])) {
                        $existing_job->art_file_id = $this->storeDocument($person->id, $job['art_file']['file'], $job['art_file']['expiration'], 'art_file', $path);
                    }
                    $existing_job->save();
                    $pc = $existing_job;
                    array_push($existing_jobs, $existing_job->id);
                }
                else {
                    $job['subactivities'] = json_encode($job['subactivities']);
                    $new_job = new PersonCompany($job);
                    $new_job->company_note_id = $this->storeDocument($person->id, $job['company_note']['file'], $job['company_note']['expiration'], 'company_note', $path);
                    $new_job->art_file_id = $this->storeDocument($person->id, $job['art_file']['file'], $job['art_file']['expiration'], 'art_file', $path);
                    $new_job->save();
                    $pc = $new_job;
                }

                foreach($job['subactivities'] as $name) {
                    \Helpers::storeSubactivity($name, $job['activity_id']);
                }

                $old_cards = $pc->cards->pluck('id')->toArray();
                $existing_cards = [];
                foreach ($job['cards'] as $card) {
                    $existing_card = Card::find($card['key']);
                    if($existing_card) {
                        $existing_card->fill($card);
                        $existing_card->save();
                        array_push($existing_cards, $existing_card->id);
                    }
                    else {
                        $new_card = new Card($card);
                        $new_card->person_company_id = $pc->id;
                        $new_card->save();
                    }
                    $removed_cards = array_diff($old_cards, $existing_cards);
                    foreach ($removed_cards as $card_id) {
                        $card = Card::where('id', $card_id)->first();
                        $card->delete();
                    }
                }
                
                $removed_jobs = array_diff($old_jobs, $existing_jobs);
                foreach ($removed_jobs as $job_id) {
                    $job = PersonCompany::where('id', $job_id)->first();
                    $job->delete();
                }

                $job_groups = $pc->groups->pluck('id')->toArray();
                \Debugbar::info($job_groups);
                $new_groups = array_diff($job['groups'], $job_groups);
                $removed_groups = array_diff($job_groups, $job['groups']);
                foreach($new_groups as $group_id){
                    $person_group = new PersonJobGroup(['job_id' => $pc->id, 'group_id' => $group_id]);
                    $person_group->save();
                }
                foreach($removed_groups as $group_id) {
                    $pc->groups()->detach($group_id);
                }
            }
        }

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

        // Stores new the documentation
        foreach ($request->documents as $key => $data) {
            if(isset($data['file'])){
                $this->storeDocument($person->id, $data['file'], $data['expiration'], $key, $path);
            }
        }

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
