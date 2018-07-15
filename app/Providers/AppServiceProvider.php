<?php

namespace App\Providers;
use Laravel\Dusk\DuskServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::component('components.input', 'input');
        Blade::component('components.panel', 'panel');
        Blade::component('components.select', 'select');
        Blade::component('components.submit-button', 'submitbutton');
        Blade::component('components.residency', 'residency');
        Blade::component('components.form-button', 'formbutton');
        Blade::component('components.form-item', 'formitem');
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
