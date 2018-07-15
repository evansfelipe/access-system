<?php
namespace App\Http\Traits;

use Illuminate\Http\Request;
use App\Residency;

trait SaveResidencyTrait {
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