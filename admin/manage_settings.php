<!DOCTYPE html>
<html dir="ltr" lang="en">
<?php 
$website = "Manage settings";
include "../layouts/head.php"; 
require "../controllers/discord.php";
require "../controllers/config.php";
?>
<body>
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
        </div>
    </div>
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full"
        data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
        <?php include "../layouts/header.php"; ?>
        <?php 
        if (!isset($_SESSION['steamid']) && $rank != "admin") { header('location: ../index'); }
        ?>
        <?php include "../layouts/side.php"; ?>
        <div class="page-wrapper">
            <div class="page-breadcrumb bg-black text-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title"><?php echo $website; ?></h4>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
            <form method="POST">
                <div class="row justify-content-left" id="card-container">
                    <?php
                    if(isset($_POST["update"])) 
                    {
                        change_env('title_webpage', $_POST['title_webpage']);
                        change_env('domain_webpage', $_POST['domain_webpage']);
                        change_env('stripe_privatekey', $_POST['stripe_privatekey']);
                        change_env('stripe_publickey', $_POST['stripe_publickey']);
                        change_env('steam_manageserverkey', $_POST['steam_manageserverkey']);
                        change_env('discord_serverid', $_POST['discord_serverid']);
                        change_env('discord_clientid', $_POST['discord_clientid']);
                        change_env('discord_secretid', $_POST['discord_secretid']);
                        change_env('discord_tokenbot', $_POST['discord_tokenbot']);
                        change_env('discord_webhook', $_POST['discord_webhook']);
                        change_env('google_adsense', $_POST['google_adsense']);
                        change_env('maintance', $_POST['maintance']);
                        change_env('font', $_POST['fonts_title']);

                        file_put_contents("../ads.txt", $_POST["google_adstxt"]);

                        header("Refresh:0");
                    }
                    ?>    
                    <div class="col-lg-12 col-xlg-6 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div style="float: left;">Stripe, Google ads, Steam Settings</div>
                                <div class="movable" style="float: right;"><i class="bi bi-arrows-move"></i></div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="stripe_privatekey">Stripe private key</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-stripe"></i></span>
                                        </div>
                                        <input id="stripe_privatekey" name="stripe_privatekey" data-secret="secret" placeholder="pk_live...." value="<?php echo PRIVATE_KEY; ?>" type="password" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="stripe_publickey">Stripe public key</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-stripe"></i></span>
                                        </div>
                                        <input id="stripe_publickey" name="stripe_publickey" data-secret="secret" placeholder="sk_live...." value="<?php echo PUBLIC_KEY; ?>" type="password" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="steam_manageserverkey">Steam manager key</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-steam"></i></span>
                                        </div>
                                        <input id="steam_manageserverkey" name="steam_manageserverkey" data-secret="secret" placeholder="7AE23...." value="<?php echo STEAM_MANAGESERVERKEY; ?>" type="password" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="steam_manageserverkey">Google announcements</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-browser-chrome"></i></span>
                                        </div>
                                        <input id="google_adsense" name="google_adsense" data-secret="secret" placeholder="ca-pub-318...." value="<?php echo GOOGLE_ADSENSE; ?>" type="password" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="steam_manageserverkey">Google announcements ads.txt</label>
                                    <div class="input-group mb-3">
                                        <input id="google_adstxt" name="google_adstxt" placeholder="google.com, pub-3186....., DIRECT, f08c47fe...." value="<?php echo file_get_contents("../ads.txt"); ?>" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-xlg-6 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div style="float: left;">Discord Settings</div>
                                <div class="movable" style="float: right;"><i class="bi bi-arrows-move"></i></div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                <label for="discord_clientid">Discord <b>CLIENT ID</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-person-circle"></i></span>
                                        </div>
                                        <input id="discord_clientid" name="discord_clientid" placeholder="735263..." value="<?php echo CLIENTID; ?>" type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="discord_secretid">Discord <b>APP SECRET</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-terminal-fill"></i></span>
                                        </div>
                                        <input id="discord_secretid" name="discord_secretid" data-secret="secret" placeholder="735263..." value="<?php echo SECRETID; ?>" type="password" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="discord_serverid">Discord <b>SERVER ID</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-database"></i></span>
                                        </div>
                                        <input id="discord_serverid" name="discord_serverid" placeholder="735263..." value="<?php echo SERVERID; ?>" type="text" class="form-control">                                        
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="discord_tokenbot">Discord <b>TOKEN BOT</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-incognito"></i></span>
                                        </div>
                                        <input id="discord_tokenbot" name="discord_tokenbot" data-secret="secret" placeholder="fasHJAD-ADajsa..." value="<?php echo BOT_TOKEN; ?>" type="password" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="discord_tokenbot">Discord announcements <b>webhook</b></label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-incognito"></i></span>
                                        </div>
                                        <input id="discord_webhook" name="discord_webhook" data-secret="secret" placeholder="https://discord.com/webhook..." value="<?php echo WEBHOOK; ?>" type="password" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-xlg-6 col-md-12">
                        <div class="card">
                                <div class="card-header">
                                    <div style="float: left;">General settings</div>
                                    <div class="movable" style="float: right;"><i class="bi bi-arrows-move"></i></div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="title_webpage">Title <b>header</b></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-hdd-rack-fill"></i></span>
                                            </div>
                                            <input id="title_webpage" name="title_webpage" placeholder="Darkness..." value="<?php echo TITLE; ?>" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="domain_webpage">Domain</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-backpack4-fill"></i></span>
                                            </div>
                                            <input id="domain_webpage" name="domain_webpage" placeholder="https://darknesscommunity.club" value="<?php echo DOMAIN_WEBPAGE; ?>" type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="maintance">Webpage <b>mode</b></label>
                                        <select name="maintance" id="maintance" class="form-select shadow-none">
                                            <?php if (MAINTANCE == 1) { ?>
                                            <option value="1">Maintance</option>
                                            <option value="0">Active</option>
                                            <?php } else { ?>
                                            <option value="0">Active</option>
                                            <option value="1">Maintance</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="fonts_title">Fonts <b>title</b></label>
                                        <select name="fonts_title" id="fonts_title" class="form-select shadow-none">
                                        <?php
                                        $directory = "../assets/plugins/fonts";
                                        $fileTimestamps = array();

                                        if (is_dir($directory)) {
                                            if ($dh = opendir($directory)) {
                                                while (($file = readdir($dh)) !== false) {
                                                    if (is_file($directory . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'otf') {
                                                        $lastModified = filemtime($directory . '/' . $file);
                                                        $fileTimestamps[$file] = $lastModified;
                                                    }
                                                }
                                                closedir($dh);

                                                arsort($fileTimestamps);

                                                foreach ($fileTimestamps as $file => $lastModified) {
                                        ?>
                                                    <option value="<?php echo $antiXss->xss_clean($file); ?>"><?php echo $antiXss->xss_clean($file); ?></option>
                                        <?php
                                                }
                                            }
                                        }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div style="float: left;">
                                        <button type="submit" name="update" class="btn btn-info text-white"><i class="bi bi-arrow-clockwise"></i> Update all</button>
                                        <a onclick="show_secret()" class="btn btn-danger text-white"><i class="bi bi-incognito"></i> Show secret all</a>
                                    </div>
                                </div>
                            </div>     
                        </div>
                    </div>
                </div> 
            </div>
            </form>
        </div>
        <script>
            function show_secret() {
                var inputElements = document.getElementsByTagName("input");

                for (var i = 0; i < inputElements.length; i++) 
                {
                    var currentInput = inputElements[i];

                    if (currentInput.type === "password" && currentInput.dataset.secret) 
                    {
                        currentInput.type = "text";
                    } 
                    else if (currentInput.type === "text" && currentInput.dataset.secret) 
                    {
                        currentInput.type = "password";
                    }
                }
            }
            
        </script>
        <script src="../assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
        <!-- Bootstrap tether Core JavaScript -->
        <script src="../assets/js/app-style-switcher.js"></script>
        <!--Wave Effects -->
        <script src="../assets/js/waves.js"></script>
        <!--Menu sidebar -->
        <script src="../assets/js/sidebarmenu.js"></script>
        <!--Custom JavaScript -->
        <script src="../assets/js/custom.js"></script>
</body>

</html>