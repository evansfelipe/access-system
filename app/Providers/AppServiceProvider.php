<?php

namespace App\Providers;
use Laravel\Dusk\DuskServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        \App\Person::observe(\App\Observers\PersonObserver::class);
        \App\Company::observe(\App\Observers\CompanyObserver::class);
        \App\Vehicle::observe(\App\Observers\VehicleObserver::class);
        \App\Activity::observe(\App\Observers\ActivityObserver::class);



        Validator::extend('unique_with', function($attribute, $value, $parameters) {
            $table       = $parameters[0]; // the table where the combination is checked.
            $field       = $parameters[1]; // the name of the field with which it must be unique.
            $field_value = request()[$field]; // gets from the request the value of the field.
            $count = DB::table($table)->where($field, $field_value)->where($attribute, $value)->count();
            return $count === 0;
        });

        Validator::replacer('unique_with', function ($message, $attribute, $rule, $parameters) {
            return 'El campo '. trans('validation.attributes.'.$attribute) .' ya existe junto al campo '. trans('validation.attributes.'.$parameters[1]) .'.';
        });


        Validator::extend('base64file', function ($attribute, $value, $parameters) {
            list($metadata, $content) = explode(',', $value);
            list($type, $extension) = explode('/', str_replace(
                ['data:', ';', 'base64'],
                ['', '', ''],
                $metadata
            ));
            // Checks if its possible to decode the content
            if(base64_decode($content, true) === false) {
                return false;
            }
            // Checks file extension
            else if(!in_array($extension, $parameters)) {
                return false;
            }
            // Checks base64 format
            else if(!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $content)) {
                return false;
            }
            else {
                return true;
            }
        });

        Validator::replacer('base64file', function ($message, $attribute, $rule, $parameters) {
            return 'El archivo debe ser de alguno de los siguientes tipos: ' . implode(', ', $parameters);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }
    }
}
