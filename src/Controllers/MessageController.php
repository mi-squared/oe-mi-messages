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
use Mi2\Messages\Models\User;

//use PatientPrivacy\PatientPrivacyService;

class MessageController extends AbstractController
{
    public function __construct()
    {
    }

    public function _action_fetch_messages()
    {
        $messages = Message::all();
        echo $messages->toJson();
    }

    public function _action_fetch_message()
    {
        $message = Message::find(1);
        $json = $message->toJson();
        echo $json;
    }

    public function _action_fetch_all_message_filters()
    {
        $userId = 4;
        $messageFilters = MessageFilter::with('messages')->get();
        $json = $messageFilters->toJson();
        echo $json;
    }

    public function _action_fetch_all_users()
    {
        $users = User::all();
        $json = $users->toJson();
        echo $json;
    }
}
