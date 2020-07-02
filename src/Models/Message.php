<?php

namespace Mi2\Messages\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Message extends Eloquent
{
    protected $table = 'aa_mi_desk_messages';

    public function attachments() {
        return $this->hasMany(Attachment::class);
    }
}
