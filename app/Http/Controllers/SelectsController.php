<?php namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\{ Zone, Company, VehicleType };

class SelectsController extends Controller
{
    public function companies(Request $request)
    {
        $companies = Company::select('id', 'name as text')->orderBy('id', 'asc');

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

        $companies = isset($name) || isset($id) || isset($ids) ? $companies->get() : [];

        return response(json_encode($companies))->header('Content-Type', 'application/json');      
    }

    public function vehicleTypes(Request $request)
    {
        $vehicles = VehicleType::select('id', 'type as text')->orderBy('id', 'asc');

        $type = $request->filter;
        $vehicles->when($type, function($query, $type) {
            return $query->orWhere('type', 'like', '%'.$type.'%');
        });

        $id = $request->id;
        $vehicles->when($id, function($query, $id) {
            return $query->orWhere('id', $id);
        });

        $ids = $request->ids;
        $vehicles->when($ids, function($query, $ids) {
            return $query->orWhereIn('id', $ids);
        });

        $vehicles = isset($type) || isset($id) || isset($ids) ? $vehicles->get() : [];

        return response(json_encode($vehicles))->header('Content-Type', 'application/json');        
    }
}
