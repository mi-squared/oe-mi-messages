<?php

namespace Mi2\Messages;

use Mi2\Messages\Models\Job;

class Api
{
    public static function createMessage($user, $subject, $body, $recipients = [], $attachments = [], $tags = [])
    {

    }

    public static function saveFile($attachmentId, $html)
    {
        $dom = new DOMDocument();
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $images = $dom->getElementsByTagName('img');
        foreach ($images as $image) {
            $src = $image->getAttribute('src');
            $type = pathinfo($src, PATHINFO_EXTENSION);
            $data = file_get_contents($src);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $image->setAttribute("src", $base64);
        }
        $html = $dom->saveHTML();
        file_put_contents("saved-".time().".html", $html);

        $doc = new \Document();
        //$file_contents = file_get_contents($patientDoc);
        //$ret = $doc->createDocument($pid, 3, $patientDocBasename, mime_content_type($patientDoc), $file_contents);
    }

    public static function processJob(Job $job)
    {
        // Get "what to do" out of the payload

        // Get the filename

        // Get the command


    }
}
