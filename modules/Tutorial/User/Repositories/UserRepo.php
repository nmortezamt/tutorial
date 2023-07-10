<?php

namespace Tutorial\User\Repositories;

use Tutorial\User\Models\User;

class UserRepo
{
    public function findByEmail(string $email)
    {
        return User::query()->where('email',$email)->first();
    }

    public function getTeachers(){
        return User::permission('teach')->get();
    }

    public function findById($id)
    {
        return User::findOrFail($id);
    }
}
