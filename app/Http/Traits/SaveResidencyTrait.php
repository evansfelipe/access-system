<?php
namespace App\Http\Traits;

use Illuminate\Http\Request;
use App\Residency;

trait SaveResidencyTrait {
    /**
     * Creates, assigns the data and saves to the database a new residency.
     * 
     * @return Integer $id: the id of the new residency.
     */
    public static function saveResidency(Request $request)
    {
        $residency = new Residency();
        $residency->street = $request->street;
        $residency->apartment = $request->apartment;
        $residency->cp = $request->cp;
        $residency->country = $request->country;
        $residency->province = $request->province;
        $residency->city = $request->city;
        $residency->save();
        return $residency->id;
    }
}