<?php

namespace Mi2\Messages\Models;

class MessageMeta extends AbstractModel
{
    protected $table = 'aa_mi_desk_message_meta';

    protected $fillable = [
        'userId', 'messageId', 'meta', 'isRead', 'isOpen'
    ];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    /*
     * Cast our JSON properties to an array
     */
    protected $casts = [
        'meta' => 'array'
    ];
}
