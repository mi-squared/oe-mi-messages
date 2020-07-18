<?php

namespace Mi2\Messages\Models;

class Job extends AbstractModel
{
    protected $table = 'aa_mi_desk_jobs';
    /*
     * Cast our JSON properties to an array
     */
    protected $casts = [
        'properties' => 'array'
    ];
}
