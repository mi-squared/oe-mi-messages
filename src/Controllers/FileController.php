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
use Mi2\Messages\Api;
use Mi2\Messages\Models\Attachment;
use Mi2\Messages\Models\File;
use Mi2\Messages\Models\Message;
use Mi2\Messages\Models\MessageFilter;
use Mi2\Messages\Models\User;
use PerfectTranscription\DocumentService;
use PerfectTranscription\PerfectTranscriptionService;

//use PatientPrivacy\PatientPrivacyService;

class FileController extends AbstractController
{
    public function __construct()
    {
    }

    public function _action_push_html()
    {
        $fileId = $this->request->getParam('fileId');
        $content = $this->request->getParam('content');
        $file = File::find($fileId);
        // Write new files and revisions
        $attachment = Attachment::find($file->attachmentId);
        $attachment = DocumentService::createFromHtml($content, $attachment);
        $attachment->refresh();
//        $revision = $attachment->revision;
//        $attachment = Attachment::find($file->attachmentId)->with(['files' => function($q) use($revision) {
//            $q->where('aa_mi_desk_files.revision', '=', $revision);
//        }])->first();
        echo $attachment->toJson();
    }

    protected function find_document_url($type, $attachmentId)
    {
        $attachment = Attachment::find($attachmentId); // Automatically gets files
        $file = null;
        foreach ($attachment->files as $file) {
            if ($file->revision == $attachment->revision &&
                $file->type == $type) {
                $document = new \Document($file->documentId);
                $pid = $document->get_foreign_id();
                // /controller.php?document&retrieve&patient_id=2&document_id=1643&as_file=true
                $baseUrl = PerfectTranscriptionService::getBaseUrl();
                $file->pointer =  "{$baseUrl}/controller.php?document&retrieve&patient_id=$pid&document_id={$file->documentId}&as_file=true";
                break;
            }
        }
        return $file;
    }

    public function _action_fetch_html()
    {
        $attachmentId = $this->request->getParam('attachmentId');
        $file = $this->find_document_url('html', $attachmentId);
        echo $file->toJson();
    }

    public function _action_fetch_pdf()
    {
        $attachmentId = $this->request->getParam('attachmentId');
        $file = $this->find_document_url('pdf', $attachmentId);
        echo $file->toJson();
    }
}
