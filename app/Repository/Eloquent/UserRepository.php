<?php

namespace App\Repository\Eloquent;

use App\Repository\RepositoryInterface;
use App\Models\User;

class UserRepository implements RepositoryInterface
{
    public function create(array $content)
    {
        User::create($content);
    }

    public function getAll($perPage = 0)
    {
        return User::all();
    }

    public function getById($id)
    {
        return User::findOrFail($id);
    }

    public function update($id, array $content)
    {
        return User::whereId($id)->update($content);
    }

    public function delete($id)
    {
        User::destroy($id);
    }

}