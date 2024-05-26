<?php

namespace App\Repository\Eloquent;

use App\Repository\RepositoryInterface;
use App\Models\Film;

class FilmRepository implements RepositoryInterface
{
    public function create(array $content)
    {
        Film::create($content);
    }

    public function getAll($perPage = 0)
    {
        return Film::all();
    }

    public function getById($id)
    {
        return Film::findOrFail($id);
    }

    public function update($id, array $content)
    {
        return Film::whereId($id)->update($content);
    }

    public function delete($id)
    {
        Film::destroy($id);
    }

}