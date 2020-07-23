<?php

namespace Mi2\Messages\Models;

class MessageMeta extends AbstractModel
{
    protected $table = 'aa_mi_desk_message_meta';

    protected $fillable = [
        'userId', 'messageId', 'isRead', 'isOpen', 'properties'
    ];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    /*
     * Cast our JSON properties to an array
     */
    protected $casts = [
        'properties' => 'array'
    ];
}
