<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\CriticFilm;
use App\Models\User;

final readonly class UpdateAverageScore
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        // TODO implement the resolver
        $retval = 0;
        try
        {
            User::findOrFail($args["user_id"]);
            $critic_film = CriticFilm::findOrFail($args["film_id"]);
            
            $votes = $critic_film->votes + 1;
            $score = $critic_film->score + $args["score"];

            $critic_film->votes = $votes;
            $critic_film->score += $score / $votes;
            $critic_film->save();

            $retval = $critic_film->score;
        }
        catch(Exception $ex)
        {
            abort(422, 'Invalid mutation');
        }
        return $retval; #"ta mere"; #$args["id"];
    }
}
