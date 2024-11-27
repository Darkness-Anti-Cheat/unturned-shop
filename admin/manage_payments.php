<!DOCTYPE html>
<html dir="ltr" lang="en">
<?php 
use voku\helper\AntiXSS;
$website = "Manage payments";
include "../layouts/head.php"; 
require "../controllers/discord.php";
require "../controllers/config.php";

include "../controllers/vendor/autoload.php";
$antiXss = new AntiXSS();
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/charts.css/dist/charts.min.css">
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
                <div class="row justify-content-md-center">
                    <div class="col col-lg-2" style="text-align: center;">
                        <div class="card">
                            <div class="card-header">
                                <b>Total payments</b>
                            </div>
                            <div class="card-body">
                                <span class="counter text-purple">
                                    <?php 
                                        foreach($queries->custom_select("SELECT count(*) FROM payments") as $row) 
                                        {
                                            echo $row[0];
                                        }
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col col-lg-2" style="text-align: center;">
                        <div class="card">
                            <div class="card-header">
                                <b>Total payments this month</b>
                            </div>
                            <div class="card-body">
                                <span class="counter text-purple">
                                    <?php 
                                        foreach($queries->custom_select("SELECT count(*) FROM payments WHERE YEAR(`date`)=YEAR(CURRENT_DATE) AND MONTH(`date`)=MONTH(CURRENT_DATE)") as $row) 
                                        {
                                            echo $row[0];
                                        }
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="card-body" style="overflow-x:auto;">
                            <div style="float: right">
                                    <label>Total Payments: <?php 
                                    foreach($queries->custom_select("SELECT count(*) FROM payments") as $row) 
                                    {
                                        echo "<b>" . $row[0] . "</b>"; 
                                    }
                                    ?> 
                                    Page: <b><?php echo filter_var(empty($_GET["page"]) || $_GET["page"] < 0  ? 1 : $_GET["page"], FILTER_SANITIZE_STRING); ?></b></label>
                                </div>
                                <div class="form-group">
                                    <label for="steamid">Find payment</label>
                                    <input class="form-control" type="text" onkeyup="search_table()" name="search_input"
                                        id="search_input" placeholder="...">
                                </div>
                                <div class="form-group">
                                    <label for="findby">Find by</label>
                                    <select name="findby" id="findby" class="form-select shadow-none">
                                        <option value="0">id</option>
                                        <option value="1">user</option>
                                        <option value="2">email</option>
                                        <option value="3">permission</option>
                                        <option value="4">ip</option>
                                        <option value="5">token</option>
                                        <option value="6">date</option>
                                    </select>
                                </div>
                                <table class="table" id="search">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">user</th>
                                            <th scope="col">email</th>
                                            <th scope="col">permission</th>
                                            <th scope="col">ip</th>
                                            <th scope="col">token</th>
                                            <th scope="col">date</th>
                                            <th scope="col">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    //NOTE - Show all pages with limit confirm
                                    if($_GET["page"] == "all") 
                                    {
                                        $limit = filter_var($_GET["limit"], FILTER_SANITIZE_STRING);

                                        $SQLtable = $queries->custom_select("SELECT user, products.rank, payments.email, payments.user_ip, payments.payment_token, payments.date FROM `payments` INNER JOIN users ON payments.user=users.id INNER JOIN products ON products.id=payments.product_id ORDER BY payments.date DESC LIMIT $limit");
                                    }
                                    else
                                    {
                                        $page = filter_var(empty($_GET["page"]) || $_GET["page"] < 0  ? 1 : $_GET["page"], FILTER_SANITIZE_STRING);
                                        $pagina_actual = isset($page) ? $page : 1;
                                        $offset = ($pagina_actual - 1) * 10;

                                        $SQLtable = $queries->custom_select("SELECT payments.id as payment_id, user, products.id as product_id, products.rank, payments.email, payments.user_ip, payments.payment_token, payments.date FROM `payments` INNER JOIN users ON payments.user=users.id INNER JOIN products ON products.id=payments.product_id ORDER BY payments.date DESC LIMIT 10 OFFSET $offset");
                                    }

                                    foreach($SQLtable as $row) 
                                    {		                  
                                    ?>
                                        <form method="POST">
                                            <tr>
                                                <td><?php echo $antiXss->xss_clean($row["payment_id"]); ?></td>
                                                <td><a href="manage_users?id=<?php echo $row["user"]; ?>"><i class="bi bi-pencil-square"></i> Edit user</a>
                                                </td>
                                                <td><b><?php echo $antiXss->xss_clean($row["email"]); ?></b></td>
                                                <td><b><?php echo $antiXss->xss_clean($row["rank"]); ?></b></td>
                                                <td><b><?php echo $antiXss->xss_clean($row["user_ip"]); ?></b> <img src="http://api.ip2flag.com/<?php echo $antiXss->xss_clean($row["user_ip"]) ?>/16"/></td>
                                                <td><b><?php echo $antiXss->xss_clean($row["payment_token"]); ?></b></td>
                                                <td class="txt-oflo"><?php echo $antiXss->xss_clean($row["date"]); ?></td>
                                                <td style="width: 180px;"><a class="btn btn-info text-white" onclick="resend(this)" payment_id="<?php echo $row["payment_id"]; ?>" product_id="<?php echo $row["product_id"]; ?>" user_id="<?php echo $row["user"]; ?>"><i class="bi bi-arrow-repeat"></i> Resend websocket</a></td>
                                            </tr>
                                            <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            </form>
                            <div class="card-footer">
                                <div style="float: left;">
                                    <a href="?page=<?php echo $_GET["page"] - 1; ?>" class="btn btn-dark text-white" <?php 
                                            if (filter_var(empty($_GET["page"]) || $_GET["page"] < 0 || $_GET["page"] == 1 || $_GET["page"] == "all")) {
                                                echo "hidden";
                                            }
                                            ?>><i
                                            class="bi bi-arrow-bar-left"></i> Previous</a>
                                    <button onclick="showall()" class="btn btn-info text-white"><i
                                            class="bi bi-exclamation-diamond-fill"></i> Show all</button>
                                </div>
                                <div style="float: right;">
                                    <a href="?page=<?php echo empty($_GET["page"]) ? 2 : $_GET["page"] + 1; ?>" class="btn btn-dark text-white" <?php 
                                            if (filter_var($_GET["page"] == "all")) {
                                                echo "hidden";
                                            }
                                            ?>><i
                                            class="bi bi-arrow-bar-right"></i> Next</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="../assets/plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js"></script>
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