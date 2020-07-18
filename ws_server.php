<?php
if (php_sapi_name() !== 'cli') {
    exit;
}

session_name("OpenEMR");
echo "In WS Server\n";
$ignoreAuth = true;
$fake_register_globals = false;
$sanitize_all_escapes = true;
$_SESSION['site_id'] = 'default';
$_SESSION['authProvider'] = 'Default';
$_SESSION['authUser'] = 'admin';
require_once __DIR__.'/../../../globals.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new \Mi2\Messages\WebSocketServer()
        )
    ),
    8081
);

$server->run();
