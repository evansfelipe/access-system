<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Person;

class SearchPersonTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     * @return void
     *@group run
     */
    public function testSearchInputs()
    {
        $this->browse(function (Browser $browser) {
            $person = Person::all()->random();
            $browser->visit(route('people.index'))
                    ->type('@last_name', $person->last_name)
                    ->type('@name', $person->name)
                    ->type('@cuil', $person->cuil)
                    ->type('@company_name', $person->company->name)
                    ->assertVue('last_name', $person->last_name, '@people-index-component')
                    ->assertVue('name', $person->name, '@people-index-component')
                    ->assertVue('cuil', $person->cuil, '@people-index-component')
                    ->assertVue('company_name', $person->company->name, '@people-index-component');
        });
    }
}
