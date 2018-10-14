<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\{ TableTimestamp, Person, Company, Vehicle, Container, Activity, Subactivity, VehicleType, Group, Gate, RiskLevel };

class ListsController extends Controller
{
    /**
     * Returns the timestamp of the last update on people's table.
     * 
     * @return Timestamp
     */
    public function peopleUpdatedAt()
    {
        return TableTimestamp::lastTimestamp('people');
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
        return TableTimestamp::lastTimestamp('companies');
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
     * Returns an array with the id of each company that matches the filter conditions.
     */
    public function companiesIdSearch(Request $request)
    {
        $companies = Company::select('id');
        // Business name
        $business_name = $request->business_name;
        $companies->when($business_name, function($query, $business_name) {
            return $query->where('business_name', 'like', '%'.$business_name.'%');
        });
        // Name
        $name = $request->name;
        $companies->when($name, function($query, $name) {
            return $query->where('name', 'like', '%'.$name.'%');
        });
        // Area
        $area = $request->area;
        $companies->when($area, function($query, $area) {
            return $query->where('area', 'like', '%'.$area.'%');
        });
        // CUIT
        $cuit = $request->cuit;
        $companies->when($cuit, function($query, $cuit) {
            return $query->where('cuit', 'like', '%'.$cuit.'%');
        });
        // Expiration
        if(isset($request->expiration_from) && isset($request->expiration_until)) {
            $companies->whereBetween('expiration', [$request->expiration_from, $request->expiration_until]);
        }
        else {
            $expiration_from = $request->expiration_from;
            $companies->when($expiration_from, function($query, $expiration_from) {
                return $query->where('expiration', '>=', $expiration_from);
            });
            $expiration_until = $request->expiration_until;
            $companies->when($expiration_until, function($query, $expiration_until) {
                return $query->where('expiration', '<=', $expiration_until);
            });
        }
        return response(json_encode($companies->get()->pluck('id')))->header('Content-Type', 'application/json');        
    }

    /**
     * Returns the date of the last update on the vehicles
     * 
     * @return Timestamp
     */
    public function vehiclesUpdatedAt()
    {
        return TableTimestamp::lastTimestamp('vehicles');
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
                'company_id'        => $vehicle->company_id,
                'company_name'      => $vehicle->company->name
            ];
        });
        return response(json_encode($vehicles))->header('Content-Type', 'application/json');        
    }

    /**
     * Returns an array with the id of each vehicle that matches the filter conditions.
     */
    public function vehiclesIdSearch(Request $request)
    {
        $vehicles = Vehicle::select('id');
        // Plate
        $plate = $request->plate;
        $vehicles->when($plate, function($query, $plate) {
            return $query->where('plate', 'like', '%'.$plate.'%');
        });
        // Brand
        $brand = $request->brand;
        $vehicles->when($brand, function($query, $brand) {
            return $query->where('brand', 'like', '%'.$brand.'%');
        });
        // Model
        $model = $request->model;
        $vehicles->when($model, function($query, $model) {
            return $query->where('model', 'like', '%'.$model.'%');
        });
        // Year
        $year = $request->year;
        $vehicles->when($year, function($query, $year) {
            return $query->where('year', 'like', '%'.$year.'%');
        });
        // Owner
        $owner = $request->owner;
        $vehicles->when($owner, function($query, $owner) {
            return $query->where('owner', 'like', '%'.$owner.'%');
        });
        // Colour
        $colour = $request->colour;
        $vehicles->when($colour, function($query, $colour) {
            return $query->where('colour', 'like', '%'.$colour.'%');
        });
        // Company ID
        $company_id = $request->company_id;
        $vehicles->when($company_id, function($query, $company_id) {
            return $query->whereIn('company_id', $company_id);
        });
        // Vehicle type
        $type_id = $request->type_id;
        $vehicles->when($type_id, function($query, $type_id) {
            return $query->whereIn('type_id', $type_id);
        });
        return response(json_encode($vehicles->get()->pluck('id')))->header('Content-Type', 'application/json');        
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
        return TableTimestamp::lastTimestamp('activities');
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
        return TableTimestamp::lastTimestamp('subactivities');
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
        return TableTimestamp::lastTimestamp('vehicle_types');
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
        return TableTimestamp::lastTimestamp('groups');  
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
        return TableTimestamp::lastTimestamp('gates');      
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
