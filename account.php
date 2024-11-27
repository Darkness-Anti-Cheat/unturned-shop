<!DOCTYPE html>
<html dir="ltr" lang="en">
<?php 
use voku\helper\AntiXSS;
$website = "Account";
include "layouts/head.php"; 
require __DIR__ . "/controllers/discord.php";
require __DIR__ . "/controllers/config.php";

$antiXss = new AntiXSS();
?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<body>
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
        </div>
    </div>
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full"
        data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
        <?php include "layouts/header.php"; ?>
        <?php 
        if (!isset($_SESSION['steamid'])) { header('location: index'); } else {
            if(!empty($_GET["code"]))
            {
                try  
                {
                    init($redirect_url, CLIENTID, SECRETID, BOT_TOKEN);

                    get_user($email = true);

                    join_guild('569332191759302657');

                    $_SESSION['discord_guilds'] = get_guilds();

                    $_SESSION['discord_connections'] = get_connections();

                    if (empty($_SESSION['discord_email'])) 
                        redirect("logout");

                    $queries->update("users", [
                        ':discord' => $_SESSION['discord_user_id'], 
                        ':steamid' => $_SESSION["steamid"]
                    ],
                    "
                    `discord`=:discord, 
                    `steamid`=:steamid WHERE `steamid`=:steamid");
                }
                catch(Exception $ex)
                {
                    die($ex);
                }
                finally
                {
                    header('location: account'); 
                }
            }
        }
        ?>
        <?php include "layouts/side.php"; ?>
        <div class="page-wrapper">
            <div class="page-breadcrumb bg-black text-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title"><?php echo $website; ?></h4>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row" id="card-container">
                    <div class="col-lg-4 col-xlg-5 col-md-12">
                        <div class="white-box">
                            <div class="user-bg"> 
                                <img width="100%" alt="user" src="assets/plugins/images/large/img1.jfif">
                                <div class="overlay-box">
                                    <div class="user-content">
                                        <a href="javascript:void(0)"><img src="<?php echo $parsed['response']['players'][0]['avatar']; ?>" class="thumb-lg img-circle" /></a>
                                        <h4 class="text-white mt-2"><?php echo $_SESSION['steam_personaname']; ?></h4>
                                        <h4 class="text-white mt-2"><?php echo empty($_SESSION["discord"]) ? '<a href="login?method=discord">Link my discord account</a>' : $_SESSION["discord"]; ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-xlg-7 col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div style="float: left;">Account</div>
                                <div class="movable" style="float: right;"><i class="bi bi-arrows-move"></i></div>
                            </div>
                            <div class="card-body">
                                <p>Your <b>ranks</b></p>
                                <div style="margin-bottom: 10px;">
                                <?php
                                foreach($queries->custom_select("SELECT products.id, products.image FROM `payments` INNER JOIN users ON payments.user=users.id INNER JOIN products ON products.id=payments.product_id WHERE users.steamid=" . $_SESSION['steamid']) as $row) 
                                {		
                                    $product_image = $row["image"];
                                    $product_id = $row["id"];
                                    echo "<span><a href='" . DOMAIN_WEBPAGE . "shop#$product_id'><img class='rounded-circle user-avatar-1' src='$product_image' alt='' height=40 width=40></a></span> ";
                                }
                                ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-xlg-5 col-md-5">
                        <div class="card">
                            <div class="card-header">
                                <div style="float: left;">Payments</div>
                                <div class="movable" style="float: right;"><i class="bi bi-arrows-move"></i></div>
                            </div>
                            <div class="card-body" style="overflow-x:auto;">
                                <table class="table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">Invoice link</th>
                                            <th scope="col">Permissions</th>
                                            <th scope="col">Payment Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if($_GET["page"] == "all") 
                                    {
                                        $SQLpayments = $queries->custom_select("SELECT products.rank, payments.email, payments.user_ip, payments.payment_token, payments.date FROM `payments` INNER JOIN users ON payments.user=users.id INNER JOIN products ON products.id=payments.product_id WHERE users.steamid=" . $_SESSION['steamid']);
                                    }
                                    else
                                    {
                                        $page = filter_var(empty($_GET["page"]) || $_GET["page"] < 0  ? 1 : $_GET["page"], FILTER_SANITIZE_STRING);
                                        $pagina_actual = isset($page) ? $page : 1;
                                        $offset = ($pagina_actual - 1) * 10;

                                        $SQLpayments = $queries->custom_select("SELECT products.rank, payments.email, payments.user_ip, payments.payment_token, payments.date FROM `payments` INNER JOIN users ON payments.user=users.id INNER JOIN products ON products.id=payments.product_id WHERE users.steamid=" . $_SESSION['steamid'] . " LIMIT 10 OFFSET $offset");
                                    }

                                    foreach($SQLpayments as $row) 
                                    {		                  
                                    ?>
                                    <div id="<?php echo $row["payment_token"]; ?>" class="modal-window">
                                        <div>
                                            <a href="#" title="Close" class="modal-close">Close</a>
                                            <div class="form-group">
                                                <label for="email">Your email</label>
                                                <input id="email" size="50" type="text" value="<?php echo $antiXss->xss_clean(empty($row["email"]) ? "NULL" : $row["email"]); ?>" class="form-control" disabled>
                                            </div>
                                            <div class="form-group">
                                                <label for="date">Date</label>
                                                <input id="date" type="text" value="<?php echo $antiXss->xss_clean($row["date"]); ?>" class="form-control" disabled>
                                            </div>
                                            <div class="form-group">
                                                <label for="payment_token">Token</label>
                                                <input id="payment_token" type="text" value="<?php echo $antiXss->xss_clean($row["payment_token"]); ?>" class="form-control" disabled>
                                            </div>
                                            <div class="form-group">
                                                <label for="user_ip">Your IP</label>
                                                <input id="user_ip" type="text" value="<?php echo $antiXss->xss_clean($row["user_ip"]); ?>" class="form-control" disabled>
                                            </div>
                                            <div class="form-group">
                                                <label for="user_ip">Permissions</label>
                                                <input id="user_ip" type="text" value="<?php echo $antiXss->xss_clean($row["rank"]); ?>" class="form-control" disabled>
                                            </div>
                                        </div>
                                    </div>
                                        <tr>
                                            <th scope="row"><a href="#<?php echo $row["payment_token"]; ?>">Open invoice</a></th>
                                            <td><?php echo $row["rank"]; ?></td>
                                            <td><b><?php echo $row["date"]; ?></b></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                <div style="float: right;">
                                <a href="?page=<?php echo empty($_GET["page"]) ? 2 : $_GET["page"] + 1; ?>" class="btn btn-dark text-white" <?php 
                                            if (filter_var($_GET["page"] == "all")) {
                                                echo "hidden";
                                            }
                                            ?>><i
                                            class="bi bi-arrow-bar-right"></i> Next</a>
                                </div>
                                <div style="float: left;">
                                    <a href="?page=<?php echo $_GET["page"] - 1; ?>"class="btn btn-dark text-white" <?php 
                                            if (filter_var(empty($_GET["page"]) || $_GET["page"] < 0 || $_GET["page"] == 1)) {
                                                echo "hidden";
                                            }
                                            ?>><i class="bi bi-arrow-bar-left"></i> Previous</a>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/js/app-style-switcher.js"></script>
    <!--Wave Effects -->
    <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/js/custom.js"></script>
</body>

</html>