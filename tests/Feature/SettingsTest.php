<?php

namespace Tests\Feature;
use Artisan;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Gate;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->security      = factory(\App\User::class)->make(['type' => \App\User::SECURITY]);
        $this->administrator = factory(\App\User::class)->make(['type' => \App\User::ADMINISTRATION]);
    }

    public function testNewGate()
    {
        $route = route('settings.new-gate');
        $max_length = Gate::LENGTHS['name']['max'];
        /**
         * Logged out tests.
         */
        $this   ->json('POST', $route, [])
                ->assertStatus(401);
        /**
         * Logged in as a security user.
         */
        $this   ->actingAs($this->security)
                ->json('POST', $route, [])
                ->assertRedirect(route('home'));
        /**
         * Logged in as a administrator user.
         */
        $this   ->actingAs($this->administrator);
        // Gate without data.
        $this   ->json('POST', $route, [])
                ->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'enabled']);
        // Gate without the name attribute.
        $this   ->json('POST', $route, ['enabled' => true])
                ->assertStatus(422)
                ->assertJsonValidationErrors(['name']);
        // Gate with an empty name.
        $this   ->json('POST', $route, ['name' => '', 'enabled' => true])
                ->assertStatus(422)
                ->assertJsonValidationErrors(['name']);
        // Gate with a name that exceeds the max character limit.
        $this   ->json('POST', $route, ['name' => str_random($max_length + 1), 'enabled' => true])
                ->assertStatus(422)
                ->assertJsonValidationErrors(['name']);
        // Gate without the enabled attribute.
        $this   ->json('POST', $route, ['name' => str_random($max_length - 10)])
                ->assertStatus(422)
                ->assertJsonValidationErrors(['enabled']);
        // Gate with a non boolean enabled value.
        $this   ->json('POST', $route, ['name' => str_random($max_length - 10), 'enabled' => 'test'])
                ->assertStatus(422)
                ->assertJsonValidationErrors(['enabled']);
        // Gate with valid data.
        $data   = ['name' => str_random($max_length - 10), 'enabled' => false];
        $this   ->json('POST', $route, $data)
                ->assertSuccessful()
                ->assertJsonStructure(['id', 'name', 'enabled'])
                ->assertJson($data);
        // Gate with non unique name (tries to save the same gate than in the previous request).
        $this   ->json('POST', $route, $data)
                ->assertStatus(422)
                ->assertJsonValidationErrors(['name']);
    }
}
