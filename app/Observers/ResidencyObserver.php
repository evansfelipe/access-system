<?php

namespace App\Observers;

use App\Residency;
use App\Location\{ City, Country, Province };

class ResidencyObserver
{
    /**
     * Handle to the residency "created" event.
     *
     * @param  \App\Residency  $residency
     * @return void
     */
    public function created(Residency $residency)
    {
        //
    }

    /**
     * Handle the residency "updated" event.
     *
     * @param  \App\Residency  $residency
     * @return void
     */
    public function updated(Residency $residency)
    {
        //
    }

    /**
     * Handle the residency "deleted" event.
     *
     * @param  \App\Residency  $residency
     * @return void
     */
    public function deleted(Residency $residency)
    {
        //
    }

    /**
     * Handle the residency "saved" event.
     *
     * @param  \App\Residency  $residency
     * @return void
     */
    public function saved(Residency $residency)
    {
        $p_country  = $residency->country;
        $p_province = $residency->province;
        $p_city     = $residency->city;

        if($p_country) {
            $country = Country::where('name', $p_country)->first();
            if(!$country) {
                $country = new Country(['name' => $p_country]);
                $country->save();
            }

            if($p_province) {
                $province = Province::where('name', $p_province)->where('country_id', $country->id)->first();
                if(!$province) {
                    $province = new Province(['name' => $p_province, 'country_id' => $country->id]);
                    $province->save();
                }

                if($p_city) {
                    $city = City::where('name', $p_city)->where('province_id', $province->id)->first();
                    if(!$city) {
                        $city = new City(['name' => $p_city, 'province_id' => $province->id]);
                        $city->save();
                    }
                }
            }
        }
    }
}
