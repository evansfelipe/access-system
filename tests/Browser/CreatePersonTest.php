<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Person;

class CreatePersonTest extends DuskTestCase
{
    private $name = 'Name';
    private $lastName = 'Last Name';

    private $shortCuil = '111111111';        // 9 characters
    private $validCuil = '11111111111111';   // 14 characters
    private $longCuil  = '1111111111111111'; // 16 characters

    private function fillInputs(Browser $browser, $cuil)
    {
        $browser->type('@last_name', $this->lastName)
                ->type('@name', $this->name)
                ->type('@cuil', $cuil)
                ->select('@sex')
                ->select('@company_id')
                ->keys('@birthday', '01012018');
    }

    /**
     * Error creating a new person since CUIL has more / fewer characters than allowed.
     */
    public function testPersonCreationCuilFail()
    {
        // A CUIL with less than 10 characters
        $this->browse(function (Browser $browser) {
            // Navigates to the people's creation route
            $browser->visit(route('people.create'));
            // Fills each input
            $this->fillInputs($browser, $this->shortCuil);
            // Submits the form and validates that the cuil was invalid
            $browser->press('@create-person-submit')
                    ->assertRouteIs('people.create')
                    ->assertPresent('@cuil-is-invalid');
        });
        // A CUIL with more than 15 characters
        $this->browse(function (Browser $browser) {
            // Navigates to the people's creation route
            $browser->visit(route('people.create'));
            // Fills each input
            $this->fillInputs($browser, $this->longCuil);
            // Submits the form and validates that the cuil was invalid
            $browser->press('@create-person-submit')
                    ->assertRouteIs('people.create')
                    ->assertPresent('@cuil-is-invalid');
        });
    }

    /**
     * A successful person creation.
     */
    public function testPersonCreationSuccess()
    {
        $this->browse(function (Browser $browser) {
            // Navigates to the people's creation route
            $browser->visit(route('people.create'));
            // Fills each input
            $this->fillInputs($browser, $this->validCuil);
            // Submits the form
            $browser->press('@create-person-submit');
            // Gets the data of the just created person
            $person = Person::where('cuil', '=', $this->validCuil)->first();
            // Validates that the browser was redirected to the view corresponding to the data of
            // the person created, and that in the same view you can see the name of that person
            $browser->assertRouteIs('people.show', $person->id)
                    ->assertSee($person->last_name)
                    ->assertSee($person->name);
        });
    }

    /**
     * Error creating a new person since the cuil entered is already in use.
     */
    public function testPersonCreationDuplicatedCuil()
    {
        // Creates a person with a no used cuil
        $this->testPersonCreationSuccess();            
        // Creates a person with the same cuil than before, so it will fail
        $this->browse(function (Browser $browser) {
            // Navigates to the people's creation route
            $browser->visit(route('people.create'));
            // Fills each input
            $this->fillInputs($browser, $this->validCuil);
            // Submits the form and validates that the route hasn't change (because the 
            // person shouldn't be created) and that the cuil was invalid
            $browser->press('@create-person-submit')
                    ->assertRouteIs('people.create')
                    ->assertPresent('@cuil-is-invalid');
        });
    }
}
