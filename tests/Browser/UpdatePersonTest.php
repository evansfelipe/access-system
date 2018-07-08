<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Person;

class UpdatePersonTest extends DuskTestCase
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
     * A successful person edit.
     */
    public function testPersonUpdateSuccess()
    {
        $person = Person::all()->random();
        $this->browse(function (Browser $browser) use ($person){
            // Navigates to the people's update route
            $browser->visit(route('people.edit', $person->id));
            // Fills each input
            $this->fillInputs($browser, $person->name . 'A', $person->last_name . 'A', $person->cuil . 'A', $this->birthday);
            // Submits the form
            $browser->press('@edit-person-submit');
            // Validates that the browser was redirected to the view corresponding to the data of
            // the person updated, and that in the same view you can see the info of that person
            $browser->assertRouteIs('people.show', $person->id)
                    ->assertSee($person->name . 'A')
                    ->assertSee($person->last_name . 'A')
                    ->assertSee($person->cuil . 'A');
        });
    }

    /**
     * Error editing a person since CUIL has fewer characters than allowed.
     */
    public function testPersonUpdateShortCuil()
    {
        $person = Person::all()->random();
        $this->browse(function (Browser $browser) use ($person){
            // Navigates to the people's update route
            $browser->visit(route('people.edit', $person->id))
                    ->type('@cuil', $this->shortCuil);
            // Submits the form and validates that the cuil was invalid
            $browser->press('@edit-person-submit')
                    ->assertRouteIs('people.edit', $person->id)
                    ->assertPresent('@cuil-is-invalid');
        });
    }

    /**
     * Error editing a person since CUIL has more characters than allowed.
     */
    public function testPersonUpdateLongCuil()
    {
        $person = Person::all()->random();
        $this->browse(function (Browser $browser) use ($person){
            // Navigates to the people's update route
            $browser->visit(route('people.edit', $person->id))
                    ->type('@cuil', $this->longCuil);
            // Submits the form and validates that the cuil was invalid
            $browser->press('@edit-person-submit')
                    ->assertRouteIs('people.edit', $person->id)
                    ->assertPresent('@cuil-is-invalid');
        });
    }

    /**
     * Error editing a new person since the cuil entered is already in use.
     */
    public function testPersonUpdateDuplicatedCuil()
    {
        $person = Person::all()->random();
        $other = Person::all()->random();

        while($person->id == $other->id) {
            $other = Person::all()->random();
        }

        // Edits a person with the same cuil than other person, so it will fail
        $this->browse(function (Browser $browser) use ($person, $other) {
            // Navigates to the people's update route
            $browser->visit(route('people.edit', $person->id))
                    ->type('@cuil', $other->cuil)
                    ->press('@edit-person-submit')
                    ->assertRouteIs('people.edit', $person->id)
                    ->assertPresent('@cuil-is-invalid');
        });
    }

    /**
     * Error editing a new person since name has more characters than allowed.
     */
    public function testPersonUpdateLongName()
    {
        $person = Person::all()->random();
        $this->browse(function (Browser $browser) use ($person) {
            // Navigates to the people's update route
            $browser->visit(route('people.edit', $person->id))
                    ->type('@name', $this->longName)
                    ->press('@edit-person-submit')
                    ->assertRouteIs('people.edit', $person->id)
                    ->assertPresent('@name-is-invalid');
        });
    }

    /**
     * Error editing a new person since last name has more characters than allowed.
     */
    public function testPersonUpdateLongLastName()
    {
        $person = Person::all()->random();
        $this->browse(function (Browser $browser) use ($person) {
            // Navigates to the people's update route
            $browser->visit(route('people.edit', $person->id))
                    ->type('@last_name', $this->longLastName)
                    ->press('@edit-person-submit')
                    ->assertRouteIs('people.edit', $person->id)
                    ->assertPresent('@last_name-is-invalid');
        });
    }

    /**
     * Error editing a new person since birthday is prior 1900.
     */
    public function testPersonUpdateOutdatedBirthday()
    {
        $person = Person::all()->random();
        $this->browse(function (Browser $browser) use ($person) {
            // Navigates to the people's creation route
            $browser->visit(route('people.edit', $person->id))
                    ->keys('@birthday', $this->birthday_prior_1900)
                    ->press('@edit-person-submit')
                    ->assertRouteIs('people.edit', $person->id)
                    ->assertPresent('@birthday-is-invalid');
        });
    }

    /**
     * Error editing a new person since birthday is a future date.
     */
    public function testPersonUpdateFutureBirthday()
    {
        $person = Person::all()->random();
        $this->browse(function (Browser $browser) use ($person) {
            // Navigates to the people's update route
            $browser->visit(route('people.edit', $person->id))
                    ->keys('@birthday', date('mdY', strtotime('+1 day'))) // As the dusk browser is an english version of chrome, date input has the mm/dd/YYYY format
                    ->press('@edit-person-submit')
                    ->assertRouteIs('people.edit', $person->id)
                    ->assertPresent('@birthday-is-invalid');
        });
    }
}
