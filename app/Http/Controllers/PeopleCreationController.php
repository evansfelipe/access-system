<?php
namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;
use App\Http\Requests\{ SavePersonRequest, SavePersonCompanyRequest, SavePersonVehicleRequest, SaveCardRequest };
use App\Http\Traits\{ Helpers };
use App\{ Person, Company, Vehicle, Residency, Card, PersonCompany, PersonVehicle, Activity };
/**
 * Controller that handles the different steps of a person creation. It validates that 
 * a step can be performed and redirects the user if not. Also, its the responsible to
 * store the data of each step in the Session and, when the last step is done, transfer
 * that data to the database.
 */
class PeopleCreationController extends Controller
{
    use Helpers;

    /**
     * Validates that the data of a given step is stored on the Session.
     */
    private function isStepCompleted($step)
    {
        $return = false;
        switch($step) {
            case 'personal-information':
                $return = Session::has('new_person.personal_information') && Session::has('new_person.residency');
                break;
            case 'working-information':
                $return = Session::has('new_person.working_information');
                break;
            case 'assign-vehicles':
                $return = Session::has('new_person.vehicles');
                break;
            case 'first-card':
                $return = Session::has('new_person.first_card');
                break;
            case 'documentation':
                // $return = Session::has('new_person.documentation');
                $return = true;
                break;
        }
        return $return;
    }

    /**
     * Cancels the person creation process. Redirects the user to the home page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel()
    {
        // Forgets all the data saved on the Session associated with the person creation.
        Session::forget('new_person');
        // Redirects the user to the home page.
        return redirect()->route('home');
    }

    public function index()
    {
        $vehicles = Vehicle::all();
        foreach($vehicles as $vehicle) { $vehicle->picked = false; }
        return view('person-creation.index')->with('companies', Company::all(['id','name'])->toJson())
                                            ->with('activities', Activity::all(['id','name'])->toJson())
                                            ->with('vehicles', $vehicles);
    }

    /**
     * Store the new person's personal information on the Session.
     *
     * @param  \App\Http\Requests\SavePersonRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function storePersonalInformation(SavePersonRequest $request)
    {
        // Creates a new residency with the given data.
        $residency = new Residency($request->toArray());
        // Creates a new person with the given data.
        $person = new Person($request->toArray());
        $person->setContact($request->toArray());
        // Stores the residency and the person on the Session variable.
        Session::put('new_person.residency', $residency);
        Session::put('new_person.personal_information', $person);
        return response('Personal Information stored successfully', 200)->header('Content-Type', 'text/plain');
    }

    /**
     * Store the new person's working information on the Session.
     *
     * @param  \App\Http\Requests\SavePersonCompanyRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeWorkingInformation(SavePersonCompanyRequest $request)
    {
        \Debugbar::info($request);
        // Creates the association between the person and the selected company and stores it on the Session.
        $personCompany = new PersonCompany($request->toArray());
        Session::put('new_person.working_information', $personCompany);
        return response('Personal Working stored successfully', 200)->header('Content-Type', 'text/plain');
    }

    /**
     * Store the assignations of vehicles with the new person on Session.
     *
     * @param  \App\Http\Requests\SavePersonVehicleRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeAssignVehicles(SavePersonVehicleRequest $request)
    {
        // Creates an array for storing the vehicles assigned to the person on the Session.
        // If there are no vehicles assigned, the array will stay empty in memory.
        $vehicles = [];
        // If there are vehicles selected on the request, adds each of them to the array
        // as an instance of the PersonVehicle Pivot.
        if(isset($request->vehicles_id)) {
            foreach($request->vehicles_id as $id) {
                array_push($vehicles, new PersonVehicle(['vehicle_id' => $id]));
            }
        }
        // Stores the array on the Session.
        Session::put('new_person.vehicles', $vehicles);
        return response('Assign Vehicles stored successfully', 200)->header('Content-Type', 'text/plain');
    }

    /**
     * Store the first card of the new person on Session.
     *
     * @param  \App\Http\Requests\SaveCardRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeFirstCard(SaveCardRequest $request)
    {
        $card = new Card($request->toArray());
        Session::put('new_person.first_card', $card);
        return response('First Card stored successfully', 200)->header('Content-Type', 'text/plain');
    }

    /**
     * Store the documentation of the new person on Session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeDocumentation(Request $request)
    {
        // dd($request);
        // return $this->storeData();
        return response('Documentation stored successfully', 200)
               ->header('Content-Type', 'text/plain');
    }

    /**
     * Transfers the data stored on the Session to the database.
     * Assigns the ids of each relationship as its needed.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePerson()
    {
        if(!$this->isStepCompleted('personal-information')){
            return response('Fail when storing', 422)->header('Content-Type', 'text/plain');
        }
        if(!$this->isStepCompleted('working-information')){
            return response('Fail when storing', 422)->header('Content-Type', 'text/plain');
        }
        if(!$this->isStepCompleted('assign-vehicles')){
            return response('Fail when storing', 422)->header('Content-Type', 'text/plain');
        }
        if(!$this->isStepCompleted('first-card')){
            return response('Fail when storing', 422)->header('Content-Type', 'text/plain');
        }
        if(!$this->isStepCompleted('documentation')){
            return response('Fail when storing', 422)->header('Content-Type', 'text/plain');
        }
        // Saves the person residency
        $residency = Session::get('new_person.residency');
        $residency->save();
        // Associates the person with the residency and saves the person
        $person = Session::get('new_person.personal_information');
        $person->residency_id = $residency->id;
        $person->save();
        // Associates the person with the company and saves the Person-Company relationship Pivot.
        $personCompany = Session::get('new_person.working_information');
        $personCompany->person_id = $person->id;
        $personCompany->save();
        // Associates each vehicle with the person and saves each Person-Vehicle relationship Pivot.
        $vehicles = Session::get('new_person.vehicles');
        foreach($vehicles as $vehicle) {
            $vehicle->person_id = $person->id;
            $vehicle->save();
        }
        // Associates the person with the card and saves the card.
        $card = Session::get('new_person.first_card');
        $card->person_id = $person->id;
        $card->save();
        /**
         * TODO: store the documentation.
         */
        // Forgets the person creation data stored on the Session.
        Session::forget('new_person');
        \Debugbar::info(Session::has('new_person'));
        // Adds the flash message
        // Session::flash('success_messages', ['La creación de la persona ha sido exitosa']);
        // Redirects the user to the just created person's profile
        // return redirect()->route('people.show', $person->id);
        return response('Store success', 200)->header('Content-Type', 'text/plain');
    }
}
