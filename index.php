<?php
use Mi2\Messages\Models\Database;

// $ignoreAuth = true; // This ignore is only used for developing outisde of OpenEMR

require_once __DIR__.'/../../../globals.php';
require_once __DIR__.'/vendor/autoload.php';


// Override the userId in every request
$userService = new \PerfectTranscription\UserService();
$user = $userService->getCurrentlyLoggedInUser();
if (count($_REQUEST) == 0) {
    $_REQUEST = json_decode(file_get_contents('php://input'),true);
}
$_REQUEST['userId'] = $user->getId();

new Database();
$app = new Mi2\Framework\App();
$app->run();
