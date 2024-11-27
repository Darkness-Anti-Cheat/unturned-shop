<?php
require_once __DIR__ . '/controllers/vendor/autoload.php';
include "controllers/db.php";
include "controllers/classes/queries.php";
require_once __DIR__ . "/controllers/discord.php";
require_once __DIR__ . "/controllers/functions.php";
require_once __DIR__ . "/controllers/config.php";
require_once __DIR__ . "/controllers/restcord.php";
require_once __DIR__ . "/controllers/steamauth/steamauth.php";
require_once __DIR__ . "/controllers/steamauth/userInfo.php";

switch($_GET['method']) 
{
    case "steam":
        redirect("index");
        break;
    case "discord":      
    try {
        $auth_url = url(CLIENTID, $redirect_url, $scopes);

        redirect($auth_url);
    } 
    catch (Exception $ex) 
    {
        die('error');
    }
    /*
    $discordId = (int)$_SESSION['user_id'];
    $discord->guild->addGuildMemberRole(['guild.id' => $server_id, 'user.id' => $discordId, 'role.id' => 1132807791141208205]);*/
    break;
}
?>