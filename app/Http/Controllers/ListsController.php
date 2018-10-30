<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\{ TableTimestamp, Person, Company, Vehicle, Container, Activity, Subactivity, VehicleType, Group, Zone, Gate };

class ListsController extends Controller
{

    public function updatedAt($list)
    {
        return TableTimestamp::lastTimestamp($list);
    }

    /**
     * @return Array<Person>
     */
    public function peopleList(Request $request)
    {
        $people = Person::leftJoin('company_people', 'people.id', '=', 'company_people.person_id')
                        ->leftJoin('companies', 'companies.id', '=', 'company_people.company_id')
                        ->groupBy('people.id', 'people.last_name', 'people.name', 'people.document_number', 'people.cuil')
                        ->select(
                            'people.id', 
                            'people.last_name', 
                            'people.name', 
                            'people.document_number', 
                            'people.cuil',
                            DB::raw('group_concat(distinct companies.name separator " / ") as company_name')
                        );

        // Sorts when its needed.
        \Helpers::orderBy($people, $request, ['last_name', 'name', 'document_number', 'cuil', 'company_name']);
        
        // Filters.
        \Helpers::whereLike($people, $request, ['last_name', 'name', 'document_number', 'cuil', 'risk', 'sex']);

        $id = $request->id;
        $people->when($id, function($query, $id) {
            return $query->whereIn('people.id', $id);
        });

        $activity_id = $request->activity_id;
        $people->when($activity_id, function($query, $activity_id) {
            return $query->whereHas('activities', function ($query) use ($activity_id) {
                return $query->whereIn('activities.id', $activity_id);
            });
        });

        // Company ID
        $company_id = $request->company_id;
        $people->when($company_id, function($query, $company_id) {
            return $query->whereHas('companies', function ($query) use ($company_id) {
                return $query->whereIn('companies.id', $company_id);
            });
        });
        // People that is not associated with a list of companies id.
        $not_company_id = $request->not_company_id;
        $people->when($not_company_id, function($query, $not_company_id) {
            return $query->whereHas('companies', function ($query) use ($not_company_id) {
                return $query->whereNotIn('companies.id', $not_company_id);
            });
        });

        // Wildcard
        \Helpers::wildcard($people, $request->wildcard, ['people.name', 'people.last_name', 'people.document_number', 'people.cuil']);

        // If a page is asked, then paginates the query. Otherwise, gets all the records.
        return  response(json_encode(isset($request->page) ? $people->paginate(10) : $people->get()))
                ->header('Content-Type', 'application/json');        
    }

    /**
     * @return Array<Company>
     */
    public function companiesList(Request $request)
    {
        $companies = Company::select('id', 'business_name', 'name', 'area', 'cuit');

        // Sorts when its needed.
        \Helpers::orderBy($companies, $request, ['business_name', 'name', 'area', 'cuit']);

        // Filters.
        \Helpers::whereIn($companies, $request, ['company_id', 'type_id']);
        \Helpers::whereLike($companies, $request, ['business_name', 'name', 'area', 'cuit']);
        // Expiration
        if(!empty($request->expiration_from) && !empty($request->expiration_until)) {
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

        // If a page is asked, then paginates the query. Otherwise, gets all the records.
        return  response(json_encode(isset($request->page) ? $companies->paginate(10) : $companies->get()))
                ->header('Content-Type', 'application/json');        
    }

    /**
     * @return Array<Vehicle>
     */
    public function vehiclesList(Request $request)
    {
        // TODO: check if type_id, type_name and allows_container are still needed.
        $vehicles = Vehicle::join('companies', 'vehicles.company_id', '=', 'companies.id')
                           ->select(
                               'vehicles.id',
                               'vehicles.plate',
                               'vehicles.brand',
                               'vehicles.model',
                               'vehicles.year',
                               'companies.name as company_name'
                            );

        // Sorts when its needed.
        \Helpers::orderBy($vehicles, $request, ['plate', 'brand', 'model', 'year', 'company_name']);

        // Filters.
        \Helpers::whereIn($vehicles, $request, ['company_id', 'type_id']);
        \Helpers::whereLike($vehicles, $request, ['plate', 'brand', 'model', 'year', 'owner', 'colour']);

        $id = $request->id;
        $vehicles->when($id, function($query, $id) {
            return $query->whereIn('vehicles.id', $id);
        });

        $not_company_id = $request->not_company_id;
        $vehicles->when($not_company_id, function($query, $not_company_id) {
            return $query->whereNotIn('company_id', $not_company_id);
        });

        // Wildcard
        $wildcard = $request->wildcard;
        $vehicles->when($wildcard, function($query, $wildcard) {
            return $query->where(function($query) use ($wildcard) {
                return $query->where('vehicles.plate',     'like', '%'.$wildcard.'%')
                            ->orWhere('vehicles.brand',   'like', '%'.$wildcard.'%')
                            ->orWhere('vehicles.model',   'like', '%'.$wildcard.'%')
                            ->orWhere('vehicles.year',    'like', '%'.$wildcard.'%')
                            ->orWhere('vehicles.colour',  'like', '%'.$wildcard.'%');
            });
        });

        // If a page is asked, then paginates the query. Otherwise, gets all the records.
        return response(json_encode(!empty($request->page) ? $vehicles->paginate(10) : $vehicles->get()))
               ->header('Content-Type', 'application/json');        
    }

    /**
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
