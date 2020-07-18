<?php

namespace Mi2\Messages\Models;

class Reply extends AbstractModel
{
    protected $table = 'aa_mi_desk_replies';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = ['body', 'userId', 'messageId'];

}
