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

        // Users
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

        // Critics
        Critic::factory()->create([
            'score' => 60.0,
            'comment' => "Meilleur film a vie!",
            'film_id' => 1,
            'user_id' => 1
        ]);
        Critic::factory()->create([
            'score' => 50.0,
            'comment' => "Pire film a vie!",
            'film_id' => 1,
            'user_id' => 2
        ]);
    }
}
