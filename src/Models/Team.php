<?php

namespace Mi2\Messages\Models;

class Team extends AbstractModel
{
    protected $table = 'aa_mi_desk_teams';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = ['name', 'filterId'];

    public function filter()
    {
        return $this->belongsTo(MessageFilter::class, 'filterId');
    }
}
