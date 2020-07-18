<?php
/**
 * Created by PhpStorm.
 * User: kchapple
 * Date: 7/29/19
 * Time: 11:00 AM
 */

namespace Mi2\Messages\Controllers;

use Mi2\DataTable\SearchFilter;
use Mi2\Framework\AbstractController;
use Mi2\Framework\Response;
use Mi2\Messages\Models\Message;
use Mi2\Messages\Models\MessageFilter;
use Mi2\Messages\Models\MessageMeta;
use Mi2\Messages\Models\MessagesFilters;
use Mi2\Messages\Models\Reply;
use Mi2\Messages\Models\User;

//use PatientPrivacy\PatientPrivacyService;

class MessageController extends AbstractController
{
    public function __construct()
    {
    }

    public function _action_fetch_message()
    {
        $messageId = $this->request->getParam('id');
        $message = Message::find($messageId);
        $json = $message->toJson();
        echo $json;
    }

    public function _action_fetch_all_message_meta_for_user()
    {
        $userId = $this->request->getParam('userId');
        $meta = MessageMeta::all()->where('userId', $userId);
        echo $meta->toJson();
    }

    public function _action_add_reply_to_message()
    {
        $userId = $this->request->getParam('userId');
        $messageId = $this->request->getParam('messageId');
        $body = $this->request->getParam('body');
        $reply = new Reply(['body' => $body, 'messageId' => $messageId, 'userId' => $userId]);
        $reply->save();
        echo $reply->toJson();
    }

    public function _action_set_message_status()
    {
        $status = $this->request->getParam('status');
        $messageId = $this->request->getParam('messageId');
        $message = Message::find($messageId);
        $message->status = $status;
        $message->save();
        echo $message->toJson();
    }

    public function _action_open_message()
    {
        $userId = $this->request->getParam('userId');
        $messageId = $this->request->getParam('messageId');
        $meta = MessageMeta::firstOrNew([
            'userId' => $userId,
            'messageId' => $messageId
        ]);
        $meta->isOpen = 1;
        $meta->save();
        echo $meta->toJson();
    }

    public function _action_close_message()
    {
        $userId = $this->request->getParam('userId');
        $messageId = $this->request->getParam('messageId');
        $meta = MessageMeta::firstOrNew([
            'userId' => $userId,
            'messageId' => $messageId
        ]);
        $meta->isOpen = 0;
        $meta->save();
        echo $meta->toJson();
    }

    public function _action_fetch_all_message_filters()
    {
        $userId = $this->request->getParam('userId');
        $messageFilters = MessageFilter::with(['messages' => function($q) use($userId) {
            $q->where('aa_mi_desk_msgs_filters.userId', '=', $userId)
                ->orderBy('aa_mi_desk_messages.subject', 'desc');
        }])->get();
//        foreach ($messageFilters as $messageFilter) {
//            foreach ($messageFilter->messages as $message) {
//                error_log("{$message->subject}\n");
//            }
//        }
        $json = $messageFilters->toJson();
        echo $json;
    }

    public function _action_move_message()
    {
        $fromFilterId = $this->request->getParam('fromFilterId');
        $toFilterId = $this->request->getParam('toFilterId');
        $messageId = $this->request->getParam('messageId');
        $userId = $this->request->getParam('userId');
        $messagesFiltersItem = MessagesFilters::where([
            'userId' => $userId,
            'messageId' => $messageId,
            'filterId' => $fromFilterId
        ])->first();
        $messagesFiltersItem->filterId = $toFilterId;
        $messagesFiltersItem->save();
        echo $messagesFiltersItem->toJson();
    }

    public function _action_set_message_meta()
    {
        $messageId = $this->request->getParam('messageId');
        $userId = $this->request->getParam('userId');
        $meta = $this->request->getParam('meta');
        $messageMeta = MessageMeta::find($meta['id']);
        if ($messageMeta) {
            $fillable = [];
            foreach ($messageMeta->getFillable() as $f) {
                $fillable[$f] = $meta[$f];
            }
            $messageMeta->fill($fillable);
            $messageMeta->save();
        }

        echo $messageMeta->toJson();
    }

    public function _action_fetch_messages()
    {
        $userId = $this->request->getParam('userId');
        $filterId = $this->request->getParam('filterId');
        $messageFilters = MessagesFilters::where([
            'userId' => $userId,
            'filterId' => $filterId
        ])->with('messages')->get();
        $messages = $messageFilters->messages;
        echo $messages->toJson();
    }

    public function _action_fetch_all_users()
    {
        $users = User::all(['id', 'username']);
        $json = $users->toJson();
        echo $json;
    }
}
