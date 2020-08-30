<?php

namespace Mi2\Messages\Models;

class Facility extends AbstractModel
{
    protected $table = 'facility';

    public $timestamps = false; // No timestamps on facility table

    protected $fillable = ['name'];

    public function messages()
    {
        
        return $this->hasMany(Message::class, 'filterId');
    }
}
