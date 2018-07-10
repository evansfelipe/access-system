<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Person;

class CreatePersonTest extends DuskTestCase
{
    private $name = 'Name';
    private $longName = 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA'; // 51 characters

    private $lastName = 'Last Name';
    private $longLastName = 'BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB'; // 51 characters

    private $birthday = '12311995'; // As the dusk browser is an english version of chrome, date input has the mm/dd/YYYY format
    private $birthday_prior_1900 = '12311899';

    private $shortCuil = '111111111';        // 9 characters
    private $validCuil = '11111111111111';   // 14 characters
    private $longCuil  = '1111111111111111'; // 16 characters

    public function setUp()
    {
        parent::setUp();
        $this->logInAs('root');
    }

    private function fillInputs(Browser $browser, $name, $lastName, $cuil, $birthday)
    {
        $browser->type('@last_name', $lastName)
                ->type('@name', $name)
                ->type('@cuil', $cuil)
                ->select('@sex')
                ->select('@company_id')
                ->keys('@birthday', $birthday);
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
            $this->fillInputs($browser, $this->name, $this->lastName, $this->validCuil, $this->birthday);
            // Submits the form
            $browser->press('@create-person-submit');
            // Gets the data of the just created person
            $person = Person::where('cuil', '=', $this->validCuil)->first();
            // Validates that the browser was redirected to the view corresponding to the data of
            // the person created, and that in the same view you can see the name of that person
            $browser->assertRouteIs('people.show', $person->id)
                    ->assertSee($this->lastName)
                    ->assertSee($this->name);
        });
    }

    /**
     * Error creating a new person since CUIL has fewer characters than allowed.
     */
    public function testPersonCreationShortCuil()
    {
        $this->browse(function (Browser $browser) {
            // Navigates to the people's creation route
            $browser->visit(route('people.create'));
            // Fills each input
            $this->fillInputs($browser, $this->name, $this->lastName, $this->shortCuil, $this->birthday);
            // Submits the form and validates that the cuil was invalid
            $browser->press('@create-person-submit')
                    ->assertRouteIs('people.create')
                    ->assertPresent('@cuil-is-invalid');
        });
    }

    /**
     * Error creating a new person since CUIL has more characters than allowed.
     */
    public function testPersonCreationLongCuil()
    {
        $this->browse(function (Browser $browser) {
            // Navigates to the people's creation route
            $browser->visit(route('people.create'));
            // Fills each input
            $this->fillInputs($browser, $this->name, $this->lastName, $this->longCuil, $this->birthday);
            // Submits the form and validates that the cuil was invalid
            $browser->press('@create-person-submit')
                    ->assertRouteIs('people.create')
                    ->assertPresent('@cuil-is-invalid');
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
            $this->fillInputs($browser, $this->name, $this->lastName, $this->validCuil, $this->birthday);
            // Submits the form and validates that the route hasn't change (because the 
            // person shouldn't be created) and that the cuil was invalid
            $browser->press('@create-person-submit')
                    ->assertRouteIs('people.create')
                    ->assertPresent('@cuil-is-invalid');
        });
    }

    /**
     * Error creating a new person since name has more characters than allowed.
     */
    public function testPersonCreationLongName()
    {
        $this->browse(function (Browser $browser) {
            // Navigates to the people's creation route
            $browser->visit(route('people.create'));
            // Fills each input
            $this->fillInputs($browser, $this->longName, $this->lastName, $this->validCuil, $this->birthday);
            // Submits the form and validates that the name was invalid
            $browser->press('@create-person-submit')
                    ->assertRouteIs('people.create')
                    ->assertPresent('@name-is-invalid');
        });
    }

    /**
     * Error creating a new person since last name has more characters than allowed.
     */
    public function testPersonCreationLongLastName()
    {
        $this->browse(function (Browser $browser) {
            // Navigates to the people's creation route
            $browser->visit(route('people.create'));
            // Fills each input
            $this->fillInputs($browser, $this->name, $this->longLastName, $this->validCuil, $this->birthday);
            // Submits the form and validates that the last name was invalid
            $browser->press('@create-person-submit')
                    ->assertRouteIs('people.create')
                    ->assertPresent('@last_name-is-invalid');
        });
    }

    /**
     * Error creating a new person since birthday is prior 1900.
     */
    public function testPersonCreationOutdatedBirthday()
    {
        $this->browse(function (Browser $browser) {
            // Navigates to the people's creation route
            $browser->visit(route('people.create'));
            // Fills each input
            $this->fillInputs($browser, $this->name, $this->lastName, $this->validCuil, $this->birthday_prior_1900);
            // Submits the form and validates that the birthday was invalid
            $browser->press('@create-person-submit')
                    ->assertRouteIs('people.create')
                    ->assertPresent('@birthday-is-invalid');
        });
    }

    /**
     * Error creating a new person since birthday is a future date.
     */
    public function testPersonCreationFutureBirthday()
    {
        // dd(date('dmY', strtotime(' +1 day')));
        $this->browse(function (Browser $browser) {
            // Navigates to the people's creation route
            $browser->visit(route('people.create'));
            // Fills each input
            $this->fillInputs($browser, $this->name, $this->lastName, $this->validCuil, date('mdY', strtotime('+1 day'))); // As the dusk browser is an english version of chrome, date input has the mm/dd/YYYY format
            // Submits the form and validates that the birthday was invalid
            $browser->press('@create-person-submit')
                    ->assertRouteIs('people.create')
                    ->assertPresent('@birthday-is-invalid');
        });
    }

}
