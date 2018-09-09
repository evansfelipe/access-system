<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location\{ City, Province, Country };

class LocationsController extends Controller
{
    public function countries()
    {
        $countries = Country::all(['id', 'name']);
        return response(json_encode($countries), 200)->header('Content-Type', 'application/json');
    }

    public function provinces($country_id = null)
    {
        $provinces = Province::select(['id','name']);
        if(isset($country_id)) {
            $provinces->where('country_id', $country_id);
        }
        return response(json_encode($provinces->get()), 200)->header('Content-Type', 'application/json');
    }

    public function cities($province_id = null)
    {
        $cities = City::select(['id','name']);
        if(isset($province_id)) {
            $cities->where('province_id', $province_id);
        }
        return response(json_encode($cities->get()), 200)->header('Content-Type', 'application/json');
    }
}
