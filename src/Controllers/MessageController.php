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
use Server\Models\Message;

//use PatientPrivacy\PatientPrivacyService;

class MessageController extends AbstractController
{
    public function __construct()
    {
    }

    public function _action_fetch_messages()
    {
        $messages = Message::all();
        return $messages->toJson();
    }

    public function _action_fetch_message()
    {
        $message = Message::find(1);
        return $message->toJson();
    }
}
