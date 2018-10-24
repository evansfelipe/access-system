<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\{ TableTimestamp, Person, Company, Vehicle, Container, Activity, Subactivity, VehicleType, Group, Zone, Gate };

class ListsController extends Controller
{

    public function updatedAt($list)
    {
        return TableTimestamp::lastTimestamp($list);
    }

    /**
     * Returns an array with each person from the system, order desc by the creation timestamp.
     * 
     * @return Array<Person>
     */
    public function peopleList(Request $request)
    {
        $timestamp = $request->timestamp;
        $people = Person::with('companies:companies.id,companies.name')
                        ->select('people.id', 'people.last_name', 'people.name', 'people.cuil')
                        ->when($timestamp, function($query, $timestamp) {
                            return $query->where('updated_at', '>', $timestamp);
                        })
                        ->orderBy('people.id', 'asc'); // Id instead of created_at for efficiency.

        // Last name
        $last_name = $request->last_name;
        $people->when($last_name, function($query, $last_name) {
            return $query->where('last_name', 'like', '%'.$last_name.'%');
        });
        // Name
        $name = $request->name;
        $people->when($name, function($query, $name) {
            return $query->where('name', 'like', '%'.$name.'%');
        });
        // Document Number
        $document_number = $request->document_number;
        $people->when($document_number, function($query, $document_number) {
            return $query->where('document_number', 'like', '%'.$document_number.'%');
        });
        // CUIT / CUIL
        $cuil = $request->cuil;
        $people->when($cuil, function($query, $cuil) {
            return $query->where('cuil', 'like', '%'.$cuil.'%');
        });
        // Risk Level
        $risk = $request->risk;
        $people->when($risk, function($query, $risk) {
            return $query->where('risk', $risk);
        });
        // Company ID
        $company_id = $request->company_id;
        $people->when($company_id, function($query, $company_id) {
            return $query->whereHas('companies', function ($query) use ($company_id) {
                return $query->whereIn('companies.id', $company_id);
            });
        });

        if(isset($request->page)) {
            $people = $people->paginate(10);
            $people->getCollection()->transform(function($person) {
                return $person->toListArray();
            });
        }
        else {
            $people = $people->get()->map(function($person) {
                return $person->toListArray();
            });
        }

        return response(json_encode($people))->header('Content-Type', 'application/json');        
    }

    /**
     * Returns an array with each company from the system, order desc by the creation timestamp.
     * 
     * @return Array<Company>
     */
    public function companiesList(Request $request)
    {
        $timestamp = $request->timestamp;
        $companies = Company::select('id','business_name','name','area','cuit')
                            ->when($timestamp, function($query, $timestamp) {
                                return $query->where('updated_at', '>', $timestamp);
                            })
                            ->orderBy('id','asc');
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

        return  response(json_encode(isset($request->page) ? $companies->paginate(10) : $companies->get()))->header('Content-Type', 'application/json');        
    }

    /**
     * Returns the list of vehicles
     * 
     * @return Array<Vehicle>
     */
    public function vehiclesList(Request $request)
    {
        $timestamp = $request->timestamp;
        $vehicles = Vehicle::select(['id','type_id','plate','brand','model','year','colour','company_id'])
                            ->when($timestamp, function($query, $timestamp) {
                                return $query->where('updated_at', '>', $timestamp);
                            })
                            ->orderBy('id','asc')
                            ->with('company:id,name');

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
        // Type ID
        $type_id = $request->type_id;
        $vehicles->when($type_id, function($query, $type_id) {
            return $query->whereIn('type_id', $type_id);
        });

        if(isset($request->page)) {
            $vehicles = $vehicles->paginate(10);
            $vehicles->getCollection()->transform(function($vehicle) {
                return $vehicle->toListArray();
            });
        }
        else {
            $vehicles = $vehicles->get()->map(function($vehicle) {
                return $vehicle->toListArray();
            });
        }
        
        return response(json_encode($vehicles))->header('Content-Type', 'application/json');        
    }

    /**
     * Returns the list of groups
     * 
     * @return Array<VehicleType>
     */
    public function groupsList(Request $request)
    {
        $timestamp = $request->timestamp;
        $groups = Group::select()
                        ->when($timestamp, function($query, $timestamp) {
                            return $query->where('updated_at', '>', $timestamp);
                        })
                        ->orderBy('created_at','asc');

        if(isset($request->page)) {
            $groups = $groups->paginate(10);
            $groups->getCollection()->transform(function($group) {
                return $group->toListArray();
            });
        }
        else {
            $groups = $groups->get()->map(function($group) {
                return $group->toListArray();
            });
        }

        return response(json_encode($groups))->header('Content-Type', 'application/json');
    }

    /**
     * Returns the list of containers
     * 
     * @return Array<Container>
     */
    public function containersList(Request $request)
    {
        $timestamp = $request->timestamp;
        $containers = Container::select(['id','series_number','format','brand','model'])
                                ->when($timestamp, function($query, $timestamp) {
                                    return $query->where('updated_at', '>', $timestamp);
                                })
                                ->orderBy('created_at','asc')
                                ->get();
        return response(json_encode($containers))->header('Content-Type', 'application/json');        
    }

    /**
     * Returns the list of activities
     * 
     * @return Array<Activity>
     */
    public function activitiesList(Request $request)
    {
        $timestamp = $request->timestamp;
        $activities = Activity::select(['id','name'])
                                ->when($timestamp, function($query, $timestamp) {
                                    return $query->where('updated_at', '>', $timestamp);
                                })
                                ->orderBy('created_at','asc')
                                ->get()
                                ->map(function($activity) {
                                    return $activity->toListArray();
                                });
        return response(json_encode($activities))->header('Content-Type', 'application/json');        
    }

    /**
     * Returns the list of subactivities
     * 
     * @return Array<Activity>
     */
    public function subactivitiesList(Request $request)
    { 
        $timestamp = $request->timestamp;
        $subactivities = Subactivity::select(['id', 'activity_id', 'name'])
                                    ->when($timestamp, function($query, $timestamp) {
                                        return $query->where('updated_at', '>', $timestamp);
                                    })
                                    ->orderBy('created_at','asc')
                                    ->get()
                                    ->map(function($subactivity) {
                                        return $subactivity->toListArray();
                                    });
        return response(json_encode($subactivities))->header('Content-Type', 'application/json');        
    }

    /**
     * Returns the list of vehicle types
     * 
     * @return Array<VehicleType>
     */
    public function vehicleTypesList(Request $request)
    {
        $timestamp = $request->timestamp;
        $types = VehicleType::select(['id','type','allows_container'])
                            ->when($timestamp, function($query, $timestamp) {
                                return $query->where('updated_at', '>', $timestamp);
                            })
                            ->orderBy('created_at','asc')
                            ->get()
                            ->map(function($type) {
                                return $type->toListArray();
                            });
        return response(json_encode($types))->header('Content-Type', 'application/json');        
    }

    /**
     * Returns the list of gates
     * 
     * @return Array<Gate>
     */
    public function gatesList(Request $request)
    {
        $timestamp = $request->timestamp;
        $gates = Gate::select()
                    ->when($timestamp, function($query, $timestamp) {
                        return $query->where('updated_at', '>', $timestamp);
                    })
                    ->orderBy('created_at','asc')
                    ->get()
                    ->map(function($gate) {
                        return $gate->toListArray();
                    });
        return response(json_encode($gates))->header('Content-Type', 'application/json');        
    }

    /**
     * Returns the list of zones
     * 
     * @return Array<Zone>
     */
    public function zonesList(Request $request)
    {
        $timestamp = $request->timestamp;
        $zones = Zone::select()
                    ->when($timestamp, function($query, $timestamp) {
                        return $query->where('updated_at', '>', $timestamp);
                    })
                    ->orderBy('created_at','asc')
                    ->get()
                    ->map(function($zone) {
                        return $zone->toListArray();
                    });
        return response(json_encode($zones))->header('Content-Type', 'application/json');        
    }
}
