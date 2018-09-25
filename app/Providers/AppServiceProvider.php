<?php

namespace App\Providers;
use Laravel\Dusk\DuskServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('base64file', function ($attribute, $value, $parameters, $validator) {
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
