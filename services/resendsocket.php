<?php
require_once __DIR__ . '/../controllers/vendor/autoload.php';
require_once "../controllers/config.php";
include "../controllers/classes/queries.php";

use WebSocket\Client;

if(!$_GET["user_id"] || !$_GET["product_id"] || !$_GET["payment_id"])
    die("¡Hello World!");


function test_connection($ip, $port) {
    try {
        $client = new Client("ws://" . $ip . ":" . $port);
    
        $client->send("test" . ":" . "test");
        $client->close();
        return 'OK';
    }
    catch (Exception $e) {
        return 'FAILED';
    }
}


try 
{
    $user_id = filter_var($_GET["user_id"], FILTER_SANITIZE_STRING);
    $product_id = filter_var($_GET["product_id"], FILTER_SANITIZE_STRING);
    $payment_id = filter_var($_GET["payment_id"], FILTER_SANITIZE_STRING);

    foreach($queries->custom_select("SELECT * FROM payments INNER JOIN users ON users.id=payments.user INNER JOIN products ON payments.product_id=products.id WHERE payments.user=$user_id AND payments.product_id=$product_id AND payments.id=$payment_id") as $row) 
    {
        $rank = $row["rank"];
        $steamid = $row["steamid"];
    }

    foreach($queries->select("products", " WHERE `id`=" . filter_var($_POST["product_id"], FILTER_SANITIZE_STRING)) as $row) 
    {
        $associated_servers = [ $row["associated_servers"] ];
    }

    $ranks_separated = explode(",", $rank);

    foreach ($ranks_separated as $rank_element) 
    {
        $SQLservers = $queries->custom_select("SELECT * FROM `servers`");
        while($row = $SQLservers ->fetch()) 
        {
            if(in_array($row["id"], $associated_servers)) {
                if(test_connection($row["websocket_ip"], $row["websocket_port"]) === "OK") 
                {
                    $client = new Client("ws://" . $row["websocket_ip"] . ":" . $row["websocket_port"]);
                    $client->send("payment" . ":" . $_POST["steamid"] . ":" . $rank_element . ":" . $row["websocket_password"]);
                    $client->close();
                }
            }
        }
    }
}
catch (Exception $e) {
    echo 'FAILED';
}