<?php
namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;
use App\Http\Requests\SavePersonRequest;
use App\Http\Traits\{ Helpers };
use App\{ Person, Company, Vehicle, Residency, Card, PersonCompany, PersonVehicle };
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
     * Given a step name, removes from the Session the data of the subsequent steps.
     */
    private function forgetFollowingSteps($current_step)
    {
        switch($current_step) {
            case 'personal_information':
                Session::forget('new_person.working_information');
                Session::forget('new_person.vehicles');
                Session::forget('new_person.first_card');
                break;
            case 'working_information':
                Session::forget('new_person.vehicles');
                Session::forget('new_person.first_card');
                break;
            case 'vehicles':
                Session::forget('new_person.first_card');
                break;
        }
    }

    /**
     * Given a step name, validates that all the previous required steps
     * to this one has been done successfully.
     */
    public function validatePreviousStep($current_step)
    {
        # TODO
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

    /**
     * Show the form for adding the new person's personal information.
     *
     * @return \Illuminate\Http\Response
     */
    public function createPersonalInformation()
    {
        $this->forgetFollowingSteps('personal_information');
        // Gets the current person's personal information under creation. It can be null.
        if($person = Session::get('new_person.personal_information')) {
            // Decodes the contact Json and stores that data on some person's attributes.
            $contact              = $person->contactToObject();
            $person->email        = $contact->email;
            $person->home_phone   = $contact->home_phone;
            $person->mobile_phone = $contact->mobile_phone;
            $person->fax          = $contact->fax;
            // If there is a residency on the Session, stores it on the person object.
            if($residency = Session::get('new_person.residency')) {
                $person->residency = $residency;
            }
        }
        // If there is one person under creation, then the view will 
        // show the personal information of that person so the user can continue 
        // his work where he had left. Otherwise, the view will show empty inputs.
        return view('person-creation.personal-information')->with('person', $person);
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
        $residency = new Residency();
        Helpers::setResidency($residency, $request);
        // Creates a new person with the given data.
        $person = new Person();
        Helpers::setPerson($person, $request);
        // Stores the residency and the person on the Session variable.
        Session::put('new_person.residency', $residency);
        Session::put('new_person.personal_information', $person);
        // Redirects to the next step.
        return redirect()->route('person-creation.working-information.create', $person->id);
    }

    /**
     * Show the form for adding the new person's working information.
     *
     * @return \Illuminate\Http\Response
     */
    public function createWorkingInformation()
    {
        // Validates that the previous step has been completed successfully.
        if(($person = Session::get('new_person.personal_information')) && ($residency = Session::get('new_person.residency'))) {
            // Once the previous step has been validated, forgets the followings step.
            $this->forgetFollowingSteps('working_information');
            // Gets the current person's working information under creation. It can be null.
            $people_companies = Session::get('new_person.working_information');
            // If there is one person under creation, then the view will 
            // show the working information of that person so the user can continue 
            // his work where he had left. Otherwise, the view will show empty inputs.
            return view('person-creation.working-information')->with('companies_data',   Helpers::companiesDataToKeyValue())
                                                              ->with('people_companies', $people_companies)
                                                              ->with('person_name',      $person->fullName());
        }
        // If the previous step hasn't been completed successfully, then redirects the user to that step.
        return redirect()->route('person-creation.personal-information.create');
    }

    /**
     * Store the new person's working information on the Session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeWorkingInformation(Request $request)
    {
        $personCompany = new PersonCompany();
        $personCompany->company_id = $request->company_id;
        $personCompany->activity_id = $request->activity_id;
        $personCompany->art = $request->art;
        $personCompany->pbip = $request->pbip;
        // try{
        //     $personCompany->save();
        // }
        // catch(\Illuminate\Database\QueryException $e){
        //     if($e->errorInfo[0] === "23000"){
        //         session()->flash("message_errors", [
        //             "Esta persona ya se encuentra asociada a la empresa."
        //         ]);
        //     }
        //     return redirect()->back()->withInput();
        // }
        Session::put('new_person.working_information', $personCompany);
        return redirect()->route('person-creation.assign-vehicles.create');
    }

    /**
     * Show the form for assigning vehicles to the new person.
     *
     * @return \Illuminate\Http\Response
     */
    public function createAssignVehicles()
    {
        // Validates that the previous step has been completed successfully.
        if($person = Session::get('new_person.working_information')) {
            $this->forgetFollowingSteps('vehicles');
            // Gets each vehicle stored on the system and those stored on the Session.
            $vehicles = Vehicle::all();
            $selectedVehicles = Session::get('new_person.vehicles') ?? [];
            // If a vehicle is already inside the Session data, then adds the picked attribute
            // as true. Otherwise, adds the picked attribute as false. Both (true and false) are 
            // saved because the picked attribute is required on the view.
            foreach($vehicles as $vehicle) {
                $vehicle->picked = !empty(array_filter($selectedVehicles, function ($selected) use ($vehicle) {
                                        return $vehicle->id == $selected->vehicle_id;
                                    }));
            }
            // Returns the view with the vehicles.
            return view('person-creation.assign-vehicles')->with('vehicles', $vehicles);
        }
        // If the previous step hasn't been completed successfully, then
        // redirects the user to that step.
        return redirect()->route('person-creation.working-information.create');
    }

    /**
     * Store the assignations of vehicles with the new person on Session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeAssignVehicles(Request $request)
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
        // Redirects to the next step.
        return redirect()->route('person-creation.first-card.create');
    }
    
    /**
     * Show the form for assigning the fist card to the new person.
     *
     * @return \Illuminate\Http\Response
     */
    public function createFirstCard()
    {
        // Gets the assign-vehicles step's data
        $vehicles = Session::get('new_person.vehicles');
        // As an empty array [] == null, we have to use the !== operator
        // because an empty array is a valid value for the previous step.
        if($vehicles !== null) {
            // Gets the data of the personal-information step. It's used to get the person's name.
            $person = Session::get('new_person.personal_information');
            // Gets the company associated with the working-information step. Its used to
            // get the company's name.
            $company = Company::find(Session::get('new_person.working_information.company_id'));
            return view('person-creation.first-card')->with('person_name', $person->fullName())
                                                     ->with('company_name', $company->name);
        }
        // If the previous step hasn't been completed successfully, then
        // redirects the user to that step.
        return redirect()->route('person-creation.assign-vehicles.create');
    }

    /**
     * Store the first card of the new person on Session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeFirstCard(Request $request)
    {
        $card = new Card();
        Helpers::setCard($card, $request);
        Session::put('new_person.first_card', $card);
        return $this->storeData();
    }

    /**
     * Transfers the data stored on the Session to the database.
     * Assigns the ids of each relationship as its needed.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    private function storeData()
    {
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
        // Forgets the person creation data stored on the Session.
        Session::forget('new_person');
        // Redirects the user to the just created person's profile
        return redirect()->route('people.show', $person->id);
    }
}
