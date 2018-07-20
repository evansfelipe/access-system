<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth','administration'])->group(function() {
    // Routes for creating a new person from scratch.
    Route::prefix('/person-creation')->name('person-creation.')->group(function() {
        // Person's personal information
        Route::get( '/personal-information', 'PeopleCreationController@createPersonalInformation')->name('personal-information.create');
        Route::post('/personal-information', 'PeopleCreationController@storePersonalInformation')->name('personal-information.store');
        // Person's working information
        Route::get( '/working-information',  'PeopleCreationController@createWorkingInformation')->name('working-information.create');
        Route::post('/working-information',  'PeopleCreationController@storeWorkingInformation')->name('working-information.store');
        // Person vehicles assignment 
        Route::get( '/assign-vehicles',      'PeopleCreationController@createAssignVehicles')->name('assign-vehicles.create');
        Route::post('/assign-vehicles',      'PeopleCreationController@storeAssignVehicles')->name('assign-vehicles.store');
        // Person first card assignment
        Route::get( '/first-card',           'PeopleCreationController@createFirstCard')->name('first-card.create');
        Route::post('/first-card',           'PeopleCreationController@storeFirstCard')->name('first-card.store');
        // Person documentation upload
        Route::get( '/documentation',        'PeopleCreationController@createDocumentation')->name('documentation.create');
        Route::post('/documentation',        'PeopleCreationController@storeDocumentation')->name('documentation.store');
        // Cancel the person creation
        Route::get( '/cancel',               'PeopleCreationController@cancel')->name('cancel');
    });

    Route::resource('/people', 'PeopleController', ['except' => ['create', 'store']]);
    Route::resource('/companies', 'CompaniesController');
    Route::resource('/people/companies', 'PeopleCompaniesController', ['except' => ['create', 'store']], ['as' => 'people']);
    Route::resource('/people/vehicles', 'PeopleVehiclesController', ['except' => ['create', 'store']], ['as' => 'people']);
    Route::resource('/cards', 'CardsController');
    Route::resource('/vehicles', 'VehiclesController');
    
});

