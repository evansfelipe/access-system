<?php namespace App\Observers;
use App\{ ModelTimestamp, Person };

class PersonObserver
{
    /**
     * Handle to the person "created" event.
     *
     * @param  \App\Person  $person
     * @return void
     */
    public function created(Person $person)
    {
    }

    /**
     * Handle the person "updated" event.
     *
     * @param  \App\Person  $person
     * @return void
     */
    public function updated(Person $person)
    {
    }

    /**
     * Handle the person "deleted" event.
     *
     * @param  \App\Person  $person
     * @return void
     */
    public function deleted(Person $person)
    {
    }

    /**
     * Handle to the person "saved" event.
     *
     * @param  \App\Person  $person
     * @return void
     */
    public function saved(Person $person)
    {
        ModelTimestamp::updateOrCreate(['model' => Person::class], ['last' => date('Y-m-d')]);
    }
}
