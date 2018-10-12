<?php namespace App\Observers;
use App\{ ModelTimestamp, Activity };

class ActivityObserver
{
    /**
     * Handle to the activity "created" event.
     *
     * @param  \App\Activity  $activity
     * @return void
     */
    public function created(Activity $activity)
    {
        //
    }

    /**
     * Handle the activity "updated" event.
     *
     * @param  \App\Activity  $activity
     * @return void
     */
    public function updated(Activity $activity)
    {
        //
    }

    /**
     * Handle the activity "deleted" event.
     *
     * @param  \App\Activity  $activity
     * @return void
     */
    public function deleted(Activity $activity)
    {
        //
    }

    /**
     * Handle to the activity "saved" event.
     *
     * @param  \App\Activity  $activity
     * @return void
     */
    public function saved(Activity $activity)
    {
        ModelTimestamp::updateOrCreate(['model' => Activity::class], ['last' => date('Y-m-d')]);
    }
}
