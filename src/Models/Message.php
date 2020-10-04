<?php

namespace Mi2\Messages\Models;

class Message extends AbstractModel
{
    protected $table = 'aa_mi_desk_messages';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = ['subject', 'body', 'userId', 'status', 'recipients', 'teams'];
    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['attachments', 'meta', 'replies', 'teams' /*, 'recipients'*/];

    /*
     * Cast our JSON properties to arrays
     */
    protected $casts = [
        'recipients' => 'array',
        'teams' => 'array'
    ];

//    public function recipients() {
//        return $this->belongsToMany(
//            User::class,
//            'aa_mi_desk_msgs_filters',
//            'userId',
//            'messageId'
//        );
//    }

    public function teams()
    {
        return $this->belongsToMany(
            Team::class,
            'aa_mi_desk_msgs_filters',
            'messageId',
            'teamId'
        );
    }

    public function attachments() {
        return $this->hasMany(Attachment::class, 'messageId');
    }

    public function meta() {
        return $this->hasMany(MessageMeta::class, 'messageId');
    }

    public function replies() {
        return $this->hasMany(Reply::class, 'messageId');
    }
}
