<?php

namespace Mi2\Messages\Models;

class File extends AbstractModel
{
    protected $table = 'aa_mi_desk_files';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = ['attachmentId', 'documentId', 'type', 'revision', 'properties'];

    /*
     * Cast our JSON properties to an array
     */
    protected $casts = [
        'properties' => 'array'
    ];

    public function document()
    {
        return $this->hasOne(Document::class, 'id', 'documentId');
    }

    public function attachment()
    {
        return $this->belongsTo(Attachment::class, 'attachmentId', 'id');
    }
}
