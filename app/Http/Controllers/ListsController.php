<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\{ Person, Company, Vehicle, Container, Activity, Subactivity, VehicleType, Group, Gate };

class ListsController extends Controller
{
    /**
     * Returns the timestamp of the last update on people's table.
     * 
     * @return Timestamp
     */
    public function peopleUpdatedAt()
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
    public function peopleList()
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
     * Returns the timestamp of the last update on companies's table.
     * 
     * @return Timestamp
     */
    public function companiesUpdatedAt()
    {
        return Company::select(['updated_at'])->orderBy('updated_at','desc')->first();        
    }

    /**
     * Returns an array with each company from the system, order desc by the creation timestamp.
     * 
     * @return Array<Company>
     */
    public function companiesList()
    {
        $companies = Company::select('id','business_name','name','area','cuit')->orderBy('created_at','desc')->get();
        return  response(json_encode($companies))->header('Content-Type', 'application/json');        
    }

    /**
     * Returns the date of the last update on the vehicles
     * 
     * @return Timestamp
     */
    public function vehiclesUpdatedAt()
    {
        $vehicle = Vehicle::select(['updated_at'])->orderBy('updated_at','desc')->first();
        return $vehicle? $vehicle->updated_at : null;        
    }

    /**
     * Returns the list of vehicles
     * 
     * @return Array<Vehicle>
     */
    public function vehiclesList()
    {
        $vehicles = Vehicle::select(['id','type_id','plate','brand','model','year','colour','company_id'])->orderBy('created_at','desc')->with('company:id,name')->get()->map(function($vehicle) {
            return [
                'id'                => $vehicle->id,
                'type_id'           => $vehicle->type_id,
                'type_name'         => $vehicle->vehicleType->type,
                'allows_container'  => $vehicle->vehicleType->allows_container,
                'plate'             => $vehicle->plate,
                'brand'             => $vehicle->brand,
                'model'             => $vehicle->model,
                'year'              => $vehicle->year,
                'colour'            => $vehicle->colour,
                'company_name'      => $vehicle->company->name
            ];
        });
        return response(json_encode($vehicles))->header('Content-Type', 'application/json');        
    }

    /**
     * Returns the date of the last update on the containers
     * 
     * @return Timestamp
     */
    public function containersUpdatedAt()
    {
        $container = Container::select(['updated_at'])->orderBy('updated_at','desc')->first();
        return $container? $container->updated_at : null;        
    }

    /**
     * Returns the list of containers
     * 
     * @return Array<Container>
     */
    public function containersList()
    {
        $containers = Container::select(['id','series_number','format','brand','model'])->orderBy('created_at','desc')->get();
        return response(json_encode($containers))->header('Content-Type', 'application/json');        
    }

    /**
     * Returns the date of the last update on the activities
     * 
     * @return Timestamp
     */
    public function activitiesUpdatedAt()
    {
        return Activity::select(['updated_at'])->orderBy('updated_at','desc')->first();        
    }

    /**
     * Returns the list of activities
     * 
     * @return Array<Activity>
     */
    public function activitiesList()
    {
        $activities = Activity::all(['id','name'])->map(function($activity) {
            return $activity->toListArray();
        });
        return response(json_encode($activities))->header('Content-Type', 'application/json');        
    }

    /**
     * Returns the date of the last update on the subactivities
     * 
     * @return Timestamp
     */
    public function subactivitiesUpdatedAt()
    {
        return Subactivity::select(['updated_at'])->orderBy('updated_at','desc')->first();        
    }

    /**
     * Returns the list of subactivities
     * 
     * @return Array<Activity>
     */
    public function subactivitiesList()
    { 
        $subactivities = Subactivity::all(['id', 'activity_id', 'name'])->map(function($subactivity) {
            return $subactivity->toListArray();
        });
        return response(json_encode($subactivities))->header('Content-Type', 'application/json');        
    }

    /**
     * Returns the date of the last update on the vehicle types
     *
     * @return Timestamp
     */
    public function vehicleTypesUpdatedAt()
    {
        return VehicleType::select(['updated_at'])->orderBy('updated_at','desc')->first();        
    }

    /**
     * Returns the list of vehicle types
     * 
     * @return Array<VehicleType>
     */
    public function vehicleTypesList()
    {
        $types = VehicleType::all(['id','type','allows_container'])->map(function($type) {
            return $type->toListArray();
        });
        return response(json_encode($types))->header('Content-Type', 'application/json');        
    }

    /**
     * Returns the date of the last update on the groups
     *
     * @return Timestamp
     */
    public function groupsUpdatedAt()
    {
        return Group::select(['updated_at'])->orderBy('updated_at','desc')->first();        
    }

    /**
     * Returns the list of groups
     * 
     * @return Array<VehicleType>
     */
    public function groupsList()
    {
        $groups = Group::all()->map(function($group) {
            return $group->toListArray();
        });
        return response(json_encode($groups))->header('Content-Type', 'application/json');        
    }

    /**
     * Returns the date of the last update on the gates
     *
     * @return Timestamp
     */
    public function gatesUpdatedAt()
    {
        return Gate::select(['updated_at'])->orderBy('updated_at','desc')->first();        
    }

    /**
     * Returns the list of gates
     * 
     * @return Array<VehicleType>
     */
    public function gatesList()
    {
        $gates = Gate::all()->map(function($gate) {
            return $gate->toListArray();
        });
        return response(json_encode($gates))->header('Content-Type', 'application/json');        
    }
}
