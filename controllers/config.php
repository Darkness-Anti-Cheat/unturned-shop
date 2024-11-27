<?php

use voku\helper\AntiXSS;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');

$dotenv->load();

$antiXss = new AntiXSS();

/* AUTO CONFIGURATION */
if(!defined('TITLE')) {
    define('TITLE', $_ENV['title_webpage']);
}
if(!defined('PUBLIC_KEY')) {
    define('PUBLIC_KEY', $_ENV['stripe_publickey']);
}
if(!defined('PRIVATE_KEY')) {
    define('PRIVATE_KEY', $_ENV['stripe_privatekey']);
}
if(!defined('CLIENTID')) {
    define('CLIENTID', $_ENV['discord_clientid']);
}
if(!defined('SERVERID')) {
    define('SERVERID', $_ENV['discord_serverid']);
}
if(!defined('SECRETID')) {
    define('SECRETID', $_ENV['discord_secretid']);
}
if(!defined('BOT_TOKEN')) {
    define('BOT_TOKEN', $_ENV["discord_tokenbot"]);
}
if(!defined('MAINTANCE')) {
    define('MAINTANCE', $_ENV['maintance']);
}
if(!defined('WEBHOOK')) {
    define('WEBHOOK', $_ENV['discord_webhook']);
}
if(!defined('STEAM_MANAGESERVERKEY')) {
    define('STEAM_MANAGESERVERKEY', $_ENV['steam_manageserverkey']);
}
if(!defined('DOMAIN_WEBPAGE')) {
    define('DOMAIN_WEBPAGE', $_ENV['domain_webpage']);
}
if(!defined('GOOGLE_ADSENSE')) {
    define('GOOGLE_ADSENSE', $_ENV['google_adsense']);
}

if(!defined('FONT')) {
    define('FONT', $_ENV['font']);
}


# SCOPES SEPARATED BY SPACE
$scopes = "identify email guilds connections";

# REDIRECT URL
$redirect_url = "";
