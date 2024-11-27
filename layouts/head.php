<?php
require_once __DIR__ . '/../controllers/vendor/autoload.php';
require_once __DIR__ . '/../controllers/sourcequery/bootstrap.php';
require_once __DIR__ . '/../controllers/classes/queries.php';
require_once __DIR__ . '/../controllers/steamauth/steamauth.php';
require_once __DIR__ . '/../controllers/steamauth/userInfo.php';
require_once __DIR__ . '/../controllers/config.php';
require_once __DIR__ . '/../controllers/functions.php';


if(MAINTANCE && !isset($_SESSION['steamid']) && !$_SESSION["rank"] == "admin") 
{
    header("location: maintance");  
}

?>
<head>
    <style>
        @font-face {
            font-family: 'Font_custom';
            src: url('<?php echo DOMAIN_WEBPAGE; ?>assets/plugins/fonts/<?php echo FONT; ?>') format('opentype');
        }
    </style>
    <!-- HTML Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords"
        content="Darkness Community Shop, Darkness Community Market, Darkness Community webpage, Unturned PvP Server, PvP Server Unturned, pvp server unturned, unturned pvp server, best server for pvp in unturned, vanilla server, semi vanilla server, imperialplugin, unturned plugins, darkness servers unturned, darkness servers">
    <meta name="description" content="Darkness Servers">
    <meta name="robots" content="noindex,nofollow">
    <meta name="theme-color" content="#FFFFFF">

    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="<?php echo DOMAIN_WEBPAGE; ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Darkness - Index">
    <meta property="og:description" content="Darkness Servers">
    <meta property="og:image" content="https://opengraph.b-cdn.net/production/documents/b72fe717-e494-4cc7-a016-eefb668866ce.png?token=MVj8yTzr1FlUJ1LIMy7Lw1X31efNCbrq4kWP9wJYSOI&height=1024&width=1024&expires=33242267311">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="<?php echo DOMAIN_WEBPAGE; ?>">
    <meta property="twitter:url" content="<?php echo DOMAIN_WEBPAGE; ?>">
    <meta name="twitter:title" content="Darkness - Index">
    <meta name="twitter:description" content="Darkness Servers">
    <meta name="twitter:image" content="https://opengraph.b-cdn.net/production/documents/b72fe717-e494-4cc7-a016-eefb668866ce.png?token=MVj8yTzr1FlUJ1LIMy7Lw1X31efNCbrq4kWP9wJYSOI&height=1024&width=1024&expires=33242267311">

    <title>Darkness - <?php echo $website ?></title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16"
        href="<?php echo DOMAIN_WEBPAGE; ?>assets/plugins/images/favicon.png">

    <!-- Custom CSS -->
    <link href="<?php echo DOMAIN_WEBPAGE; ?>assets/css/style.min.css" rel="stylesheet">
    <link href="<?php echo DOMAIN_WEBPAGE; ?>assets/css/body.css" rel="stylesheet">

    <!-- FIX ME -->
    <!--<script src="https://darknesscommunity.club/assets/js/snowstorm-min.js"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/js/sweetalert.min.js"></script>
    <script src="https://kit.fontawesome.com/cf16aa50c1.js" crossorigin="anonymous"></script>
    <script data-ad-client="ca-pub-3186822807904753" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <link href="<?php echo DOMAIN_WEBPAGE; ?>assets/css/jquery.minipreview.css" rel="stylesheet">
    <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/js/jquery.minipreview.js"></script>
    <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/js/btn-mode.js"></script>
    <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/js/find-function.js"></script>
    <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/js/sortable-drag.js" defer></script>
    <script type="text/javascript" src="https://unpkg.com/lightweight-charts/dist/lightweight-charts.standalone.production.js"></script>
    <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/js/MultiSelect.js" defer></script>

    <!-- Websocket for manage_servers -->
    <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/js/websocket.js"></script>
    <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
    <link href="<?php echo DOMAIN_WEBPAGE; ?>assets/css/MultiSelect.css" rel="stylesheet" type="text/css">
</head>