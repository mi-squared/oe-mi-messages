<?php


namespace Mi2\Messages\Models;


class UsersTeams extends AbstractModel
{
    protected $table = 'aa_mi_desk_users_teams';

    public $timestamps = false;

    protected $fillable = ['userId', 'teamId'];

    public function users()
    {
        return $this->hasMany(User::class, 'id', 'userId');
    }

    public function filters()
    {
        return $this->hasMany(Team::class, 'id', 'teamId');
    }
}
