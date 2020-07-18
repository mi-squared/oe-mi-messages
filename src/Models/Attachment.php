<?php

namespace Mi2\Messages\Models;

class Attachment extends AbstractModel
{
    protected $table = 'aa_mi_desk_attachments';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = ['name', 'messageId', 'type', 'pointer', 'properties', 'revision'];

    /*
     * Cast our JSON properties to an array
     */
    protected $casts = [
        'properties' => 'array'
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['files'];

    public function files()
    {
        return $this->hasMany(File::class, 'attachmentId');
    }
}
