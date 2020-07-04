<?php

namespace Mi2\Messages\Models;

class Message extends AbstractModel
{
    protected $table = 'aa_mi_desk_messages';

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['attachments'];

    public function attachments() {
        return $this->hasMany(Attachment::class, 'messageId');
    }
}
