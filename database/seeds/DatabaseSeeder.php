<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Company::class, 10)->create();
        factory(App\Vehicle::class, 100)->create();
        // factory(App\Person::class, 5)->create();
        DB::table('users')->insert([
            'name' => 'Root',
            'email' =>'root@example.com',
            'password' => bcrypt('secret'),
            'type' => \App\User::ROOT
        ]);
        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' =>'administrator@example.com',
            'password' => bcrypt('secret'),
            'type' => \App\User::ADMINISTRATION
        ]);
        DB::table('users')->insert([
            'name' => 'Security',
            'email' =>'security@example.com',
            'password' => bcrypt('secret'),
            'type' => \App\User::SECURITY
        ]);
        DB::table('activities')->insert(['name' => 'Pesquero']);
        DB::table('activities')->insert(['name' => 'Control de calidad']);
    }
}
