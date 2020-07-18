<?php


namespace Mi2\Messages\Models;


class MessagesFilters extends AbstractModel
{
    protected $table = 'aa_mi_desk_msgs_filters';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = ['userId', 'messageId', 'filterId'];

    public function messages()
    {
        return $this->hasMany(Message::class, 'id', 'messageId');
    }
}
