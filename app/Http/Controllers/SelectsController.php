<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\{ Company, VehicleType, Activity, Subactivity };
use App\Location\{ Country };

class SelectsController extends Controller
{
    public function companies(Request $request)
    {
        $companies = Company::select('id', 'name as text')->orderBy('id', 'desc');

        $name = $request->filter;
        $companies->when($name, function($query, $name) {
            return $query->orWhere('name', 'like', '%'.$name.'%');
        });

        $name = $request->filter;
        $companies->when($name, function($query, $name) {
            return $query->orWhere('business_name', 'like', '%'.$name.'%');
        });

        $id = $request->id;
        $companies->when($id, function($query, $id) {
            return $query->orWhere('id', $id);
        });

        $ids = $request->ids;
        $companies->when($ids, function($query, $ids) {
            return $query->orWhereIn('id', $ids);
        });

        if(empty($name) && empty($id) && empty($ids)) {
            $companies->take(10);
        }

        return response(json_encode($companies->get()))->header('Content-Type', 'application/json');      
    }

    public function vehicleTypes(Request $request)
    {
        $types = VehicleType::select('id', 'type as text')->orderBy('id', 'desc');

        $type = $request->filter;
        $types->when($type, function($query, $type) {
            return $query->orWhere('type', 'like', '%'.$type.'%');
        });

        $id = $request->id;
        $types->when($id, function($query, $id) {
            return $query->orWhere('id', $id);
        });

        $ids = $request->ids;
        $types->when($ids, function($query, $ids) {
            return $query->orWhereIn('id', $ids);
        });

        if(empty($type) && empty($id) && empty($ids)) {
            $types->take(10);
        }

        return response(json_encode($types->get()))->header('Content-Type', 'application/json');        
    }

    public function activities(Request $request)
    {
        $activities = Activity::select('id', 'name as text')->orderBy('id', 'desc');

        $name = $request->filter;
        $activities->when($name, function($query, $name) {
            return $query->orWhere('name', 'like', '%'.$name.'%');
        });
        
        $id = $request->id;
        $activities->when($id, function($query, $id) {
            return $query->orWhere('id', $id);
        });

        $ids = $request->ids;
        $activities->when($ids, function($query, $ids) {
            return $query->orWhereIn('id', $ids);
        });

        if(empty($name) && empty($id) && empty($ids)) {
            $activities->take(10);
        }

        return response(json_encode($activities->get()))->header('Content-Type', 'application/json');   
    }

    public function subactivities(Request $request)
    {
        $subactivities = Subactivity::select('id', 'name')->orderBy('id', 'desc');

        $activity_id = $request->activity_id;
        $subactivities->when($activity_id, function($query, $activity_id) {
            return $query->whereIn('activity_id', $activity_id);
        });

        $filter = $request->filter;
        $subactivities->when($filter, function($query, $filter) {
            return $query->orWhere('name', 'like', '%'.$filter.'%');
        });

        $name = $request->name;
        $subactivities->when($name, function($query, $name) {
            return $query->orWhereIn('name', $name);
        });

        $id = $request->id;
        $subactivities->when($id, function($query, $id) {
            return $query->orWhere('id', $id);
        });

        $ids = $request->ids;
        $subactivities->when($ids, function($query, $ids) {
            return $query->orWhereIn('id', $ids);
        });

        if(empty($name) && empty($id) && empty($ids)) {
            $subactivities->take(10);
        }

        $subactivities = $subactivities->get()->map(function($subactivity) {
            return [
                'id'   => $subactivity->name,
                'name' => $subactivity->name,
            ];
        });

        return response(json_encode($subactivities))->header('Content-Type', 'application/json');   
    }

    public function homelands(Request $request)
    {
        $homelands = Country::select('id', 'name')->orderBy('id', 'desc');

        $filter = $request->filter;
        $homelands->when($filter, function($query, $filter) {
            return $query->orWhere('name', 'like', '%'.$filter.'%');
        });
        
        $name = $request->name;
        $homelands->when($name, function($query, $id) {
            return $query->orWhere('name', 'like', '%'.$id.'%');
        });

        $ids = $request->ids;
        $homelands->when($ids, function($query, $ids) {
            return $query->orWhereIn('id', $ids);
        });

        if(empty($name) && empty($id) && empty($ids)) {
            $homelands->take(10);
        }

        $homelands = $homelands->get()->map(function($homeland) {
            return [
                'id'   => $homeland->name,
                'name' => $homeland->name,
            ];
        });

        return response(json_encode($homelands))->header('Content-Type', 'application/json');   
    }
}
