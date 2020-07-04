<?php
use Mi2\Messages\Models\Database;
require_once __DIR__.'/../../../globals.php';
//require_once __DIR__.'/vendor/autoload.php';
new Database();
$app = new Mi2\Framework\App();
$app->run();
