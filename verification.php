<?php
require __DIR__ . "/controllers/discord.php";
require __DIR__ . "/controllers/config.php";

$auth_url = url($client_id, $redirect_url, $scopes);
ob_start();
header('Location: '. $auth_url);
ob_end_flush();
die();
?>