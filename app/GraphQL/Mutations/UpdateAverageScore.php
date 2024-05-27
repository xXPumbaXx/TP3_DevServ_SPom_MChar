<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\CriticFilm;
use App\Models\User;

final readonly class UpdateAverageScore
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        $new_score = 0;
        $new_votes = 0;
        try
        {
            User::findOrFail($args["user_id"]); // Safety

            $critic_film = CriticFilm::findOrFail($args["film_id"]);
            
            $score = ($critic_film->score * $critic_film->votes) + $args["score"];
            $votes = $critic_film->votes + 1;

            $critic_film->score = $score / $votes;
            $critic_film->votes = $votes;
            $critic_film->save();

            $new_score = number_format($critic_film->score, 2, '.', '');
            $new_votes = $votes;
        }
        catch(Exception $ex)
        {
            abort(422, 'Invalid mutation');
        }
        return array(
            "score" => $new_score,
            "votes" => $new_votes,
            "film_id" => $args["film_id"]
        );
    }
}
