<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\Http\Requests\SavePersonRequest;

use \App\{ Person, Company, Vehicle, Residency, PersonCompany, PersonVehicle };
use App\Http\Traits\{ Helpers };

use Session;

class PeopleCreationController extends Controller
{
    use Helpers;
    /**
     * Cancels the person creation process.
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
        // Gets the current person's personal information under creation. It can be null.
        $person = Session::get('new_person.personal_information');
        // If there is one person under creation, then the view will 
        // show the personal information of that person so the user can continue 
        // his work where he had left. Otherwise, the view will show empty inputs.
        return view('people.create')->with('person', $person);
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
        Session::put('new_person.personal_information', $person);
        Session::put('new_person.residency', $residency);
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
            // Gets the current person's working information under creation. It can be null.
            $people_companies = Session::get('new_person.working_information');
            // If there is one person under creation, then the view will 
            // show the working information of that person so the user can continue 
            // his work where he had left. Otherwise, the view will show empty inputs.
            return view('people-companies.create')->with('companies_data', $this->companiesDataToKeyValue())
                                                  ->with('person_name', $person->fullName())
                                                  ->with('people_companies', $people_companies);
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
        // $personCompany->person_id = $person->id;
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
            // Gets each vehicle stored on the system.
            $vehicles = Vehicle::all();
            if($selectedVehicles = Session::get('new_person.vehicles')) {
                foreach($selectedVehicles as $selected) {
                    $found = $vehicles->search(function ($vehicle, $key) use ($selected) {
                        return $vehicle->id == $selected->vehicle_id;
                    });
                    if($found !== false) {
                        $vehicles[$found]->picked = true;
                    }
                }
            }
            // Returns the view with the vehicles.
            return view('people-vehicles.create')->with('vehicles', $vehicles);
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
        $vehicles = [];
        if(isset($request->vehicles_id)) {
            foreach($request->vehicles_id as $id) {
                $peopleVehicle = new PersonVehicle();
                $peopleVehicle->vehicle_id = $id;
                array_push($vehicles, $peopleVehicle);
            }
        }
        Session::put('new_person.vehicles', $vehicles);
        return redirect()->route('person-creation.first-card.create');
    }
    
    /**
     * Show the form for assigning the fist card to the new person.
     *
     * @return \Illuminate\Http\Response
     */
    public function createFirstCard()
    {
        return "create";
    }

    /**
     * Store the first card of the new person on Session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeFirstCard(Request $request)
    {
        return "store";
    }
}
