<?php
use Mi2\Messages\Models\Database;

// $ignoreAuth = true; // This ignore is only used for developing outisde of OpenEMR

require_once __DIR__.'/../../../globals.php';
// Dependencies (Eloquent, mi-framework) come from OpenEMR's root composer autoloader.


// Override the userId in every request
$userService = new \PerfectTranscription\UserService();
$user = $userService->getCurrentlyLoggedInUser();
if ($user === null) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Not logged in']);
    exit;
}
if (count($_REQUEST) == 0) {
    $_REQUEST = json_decode(file_get_contents('php://input'), true) ?: [];
}
$_REQUEST['userId'] = $user->getId();

// Every action is authorized before it runs (the controllers load records by id).
\Mi2\Messages\AccessGuard::check($_REQUEST, $user->getId());

new Database();
$app = new Mi2\Framework\App();
$app->run();
