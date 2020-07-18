<?php
use Mi2\Messages\Models\Database;

$ignoreAuth = true;

if (count($_REQUEST) == 0) {
    $_REQUEST = json_decode(file_get_contents('php://input'),true);
}

require_once __DIR__.'/../../../globals.php';
require_once __DIR__.'/vendor/autoload.php';

new Database();
$app = new Mi2\Framework\App();
$app->run();
