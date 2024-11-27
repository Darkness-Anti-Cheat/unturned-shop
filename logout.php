<?php
require __DIR__ . "/controllers/functions.php";

session_start();

session_destroy();

redirect("index");

?>
