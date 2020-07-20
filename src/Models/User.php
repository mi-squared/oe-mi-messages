<?php

namespace Mi2\Messages\Models;

class User extends AbstractModel
{
    protected $table = 'users';

    public function teams()
    {
        return $this->belongsToMany(
            Team::class,
            'aa_mi_desk_users_teams',
            'userId',
            'teamId'
        );
    }
}
