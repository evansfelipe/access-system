<?php namespace App\Observers;
use App\{ ModelTimestamp, Company };

class CompanyObserver
{
    /**
     * Handle to the company "created" event.
     *
     * @param  \App\Company  $company
     * @return void
     */
    public function created(Company $company)
    {
        //
    }

    /**
     * Handle the company "updated" event.
     *
     * @param  \App\Company  $company
     * @return void
     */
    public function updated(Company $company)
    {
        //
    }

    /**
     * Handle the company "deleted" event.
     *
     * @param  \App\Company  $company
     * @return void
     */
    public function deleted(Company $company)
    {
        //
    }

    /**
     * Handle to the company "saved" event.
     *
     * @param  \App\Company  $company
     * @return void
     */
    public function saved(Company $company)
    {
        ModelTimestamp::updateOrCreate(['model' => Company::class], ['last' => date('Y-m-d')]);
    }
}
