<?php namespace App\Observers;
use App\{ ModelTimestamp, Vehicle };

class VehicleObserver
{
    /**
     * Handle to the vehicle "created" event.
     *
     * @param  \App\Vehicle  $vehicle
     * @return void
     */
    public function created(Vehicle $vehicle)
    {
        //
    }

    /**
     * Handle the vehicle "updated" event.
     *
     * @param  \App\Vehicle  $vehicle
     * @return void
     */
    public function updated(Vehicle $vehicle)
    {
        //
    }

    /**
     * Handle the vehicle "deleted" event.
     *
     * @param  \App\Vehicle  $vehicle
     * @return void
     */
    public function deleted(Vehicle $vehicle)
    {
        //
    }

    /**
     * Handle to the vehicle "saved" event.
     *
     * @param  \App\Vehicle  $vehicle
     * @return void
     */
    public function saved(Vehicle $vehicle)
    {
        ModelTimestamp::updateOrCreate(['model' => Vehicle::class], ['last' => date('Y-m-d')]);
    }

}
