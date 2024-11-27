<?php
require_once __DIR__ . '/../controllers/vendor/autoload.php';

use WebSocket\Client;

if(!$_GET["ip"] || !$_GET["port"])
    die("¡Hello World!");

try {
    $client = new Client("ws://" . $_GET["ip"] . ":" . $_GET["port"]);

    $client->send("test" . ":" . "test");
    $client->close();
    echo 'OK';
}
catch (Exception $e) {
    echo 'FAILED';
}