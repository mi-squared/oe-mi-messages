<?php

namespace Mi2\Messages\Models;

class MessageFilter extends AbstractModel
{
    protected $table = 'aa_mi_desk_filters';

    public function messages()
    {
        return $this->belongsToMany(
            Message::class,
            'aa_mi_desk_msgs_filters',
            'filterId',
            'messageId');
    }
}
