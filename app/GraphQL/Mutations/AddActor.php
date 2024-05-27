<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\CriticFilm;
use App\Models\Film;
use App\Models\Actor;

final readonly class AddActor
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $actor;
        try{
            $actor = Actor::create([
                'last_name' => $args["last_name"], 
                'first_name' => $args["first_name"], 
                'birthdate' => $args["birthdate"]
            ]);

            foreach($args["update_film_image"] as $film_image){
                Film::findOrFail($film_image["id"])->image = $film_image["image"];
            }

            foreach($args["associated_films"] as $film_id){
                Film::findOrFail($film_id)->actors()->attach($actor);
            }
        }
        catch(Exception $ex)
        {
            abort(422, 'Invalid mutation');
        }
        return $actor;
    }
}
