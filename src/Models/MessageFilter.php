<?php

namespace Mi2\Messages\Models;

class MessageFilter extends AbstractModel
{
    protected $table = 'aa_mi_desk_filters';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['messages'];

    public function messages()
    {
        return $this->belongsToMany(
            Message::class,
            'aa_mi_desk_msgs_filters',
            'filterId',
            'messageId');
    }
}
