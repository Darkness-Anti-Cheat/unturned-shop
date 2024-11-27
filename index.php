<!DOCTYPE html>
<html dir="ltr" lang="en">
<?php 
$website = "Index";
include "layouts/head.php"; 
include "controllers/restcord.php";

if (isset($_SESSION['steamid'])) 
{
    $getUser = $queries->custom_select("SELECT * FROM users WHERE steamid=" . $_SESSION['steamid']);
    foreach($getUser as $row) 
    {
        if($row["steamid"] != $_SESSION['steamid']) {
            $queries->insert("users", [
                ':null' => null,
                ':steamid' => $_SESSION['steamid'], 
                ':discord' => NULL, 
                ':ip' => get_client_ip(), 
                ':rank' => 'member', 
                ':status' => 0
            ]);
        }
    }	
}
?>
<body>
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
        </div>
    </div>
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full"
        data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
        <?php include "layouts/header.php"; ?>
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
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-12">
                        <div class="white-box analytics-info">
                            <h3 class="box-title">Discord users</h3>
                            <ul class="list-inline two-part d-flex align-items-center mb-0">
                                <li>
                                    <div id="sparklinedash"><canvas width="67" height="30"
                                            style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas>
                                    </div>
                                </li>
                                <li class="ms-auto">
                                    <span class="counter text-success">
                                        <?php 
                                        try 
                                        {
                                            global $ids;
                                            echo count(getTotalUsersCount($ids, $limit, $discord_client)); 
                                        }
                                        catch(Exception $e)
                                        {
                                            echo "0";
                                        }
                                        ?>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="white-box analytics-info">
                            <h3 class="box-title">Total Purchases</h3>
                            <ul class="list-inline two-part d-flex align-items-center mb-0">
                                <li>
                                    <div id="sparklinedash2"><canvas width="67" height="30"
                                            style="display: inline-block; width: 67px; height: 30px; vertical-align: top;"></canvas>
                                    </div>
                                </li>
                                <li class="ms-auto"><span class="counter text-purple">
                                <?php 
                                    foreach($queries->custom_select("SELECT count(*) FROM payments") as $row) 
                                    {
                                        echo $row[0];
                                    }
                                ?>
                                </span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <div class="row align-items-center">
                            <iframe src="https://discord.com/widget?id=<?php echo SERVERID; ?>&theme=dark" width="1000" height="300" allowtransparency="true" frameborder="0" sandbox="allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->

    <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/js/app-style-switcher.js"></script>
    <script
        src="<?php echo DOMAIN_WEBPAGE; ?>assets/plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js">
    </script>
    <!--Wave Effects -->
    <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/js/custom.js"></script>
    <!--This page JavaScript -->
    <!--chartis chart-->
    <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/plugins/bower_components/chartist/dist/chartist.min.js"></script>
    <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/plugins/bower_components/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/js/pages/dashboards/dashboard1.js"></script>
</body>

</html>