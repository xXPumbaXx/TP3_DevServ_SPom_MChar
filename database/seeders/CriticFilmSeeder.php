<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CriticFilmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = json_decode(file_get_contents(database_path() . '/seeders/data_source.json'))->data;
        foreach($json as $film)
        {
            $average = 0;
            $votes = 0;
            foreach($film->reviews as $review)
            {
                $average += $review->score;
                $votes += $review->votes;
            }
            if($votes === 0) $average = 0;
            else $average = $average / $votes;
            $sql = "INSERT INTO critic_film (created_at, film_id, score) VALUES ('". date("Y-m-d H:i:s") . "', " . $film->id . ", '" . number_format((float)$average, 1, '.', '') . "')";
            #var_dump($sql);
            DB::statement($sql);
        }
    }
}
