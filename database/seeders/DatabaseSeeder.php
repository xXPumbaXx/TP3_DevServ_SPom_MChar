<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Critic;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([            
            LanguageSeeder::class,
            FilmSeeder::class,
            ActorSeeder::class,
            FilmActorSeeder::class,
            RoleSeeder::class,
            CriticFilmSeeder::class
        ]);
        User::factory(10)->
        has(Critic::factory(30))            
        ->create();

        User::factory()->create([
            'login' => '1844680',
            'password' => bcrypt("Oh qu'il est beau le lavabo!"),
            'email' => 'mcharette@csfoy.qc.ca',
            'first_name' => 'Mikaël',
            'last_name' => 'Charette',
            'role_id' => 2
        ]);
        User::factory()->create([
            'login' => '1944680',
            'password' => bcrypt("Hello World!"),
            'email' => 'jcharette@csfoy.qc.ca',
            'first_name' => 'Jérémi',
            'last_name' => 'Charette',
            'role_id' => 1
        ]);

        //Ne sera pas fait dans le cadre de ce TP, les users et les critiques seront créés par vous
        //User::factory(10)->has(Critic::factory(30))->create();
    }
}
