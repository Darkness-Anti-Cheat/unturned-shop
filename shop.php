<!DOCTYPE html>
<html dir="ltr" lang="en">

<?php 
use voku\helper\AntiXSS;

$website = "Shop";
include "layouts/head.php"; 
include __DIR__.'/controllers/vendor/autoload.php';
$antiXss = new AntiXSS();
$Parsedown = new Parsedown();
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
                    <div class="alert alert-danger" role="alert">
                        Currency conversion are temporary disabled, only EUROS are working
                    </div>
                </div>
                <div class="row justify-content-left">
                    <div style="width: 100px; float: left; margin-bottom: 10px;">
                        <select class="form-control" id="paymentcurrency" name="paymentcurrency"
                            onchange="location = this.value;">
                            <?php  
                            $currency = $_GET['currency'];
                            if($currency == "eur") {  ?>
                            <option value="shop?currency=eur">EUR</option>
                            <option value="shop?currency=usd">USD</option>
                            <?php } else if($currency == "usd") { ?>
                            <option value="shop?currency=usd">USD</option>
                            <option value="shop?currency=eur">EUR</option>
                            <?php } else {  ?>
                            <option>EUR</option>
                            <option value="shop?currency=usd">USD</option>
                            <?php $currency = "eur"; }  ?>
                        </select>
                    </div>
                </div>
                <div class="row justify-content-left">
                    <?php
                    foreach($queries->select("products", "ORDER BY `id` ASC") as $row) 
                    {						
                        if(!$row["deleted"]) {
                    ?>
                    <div id="<?php echo $row["id"]; ?>" class="modal-window">
                        <div class="modal-body">
                            <a href="#close" title="Close" class="modal-close">Close</a>
                            <h2><?php echo $row["name"]; ?></h2>
                            <?php echo $antiXss->xss_clean($Parsedown->text($row["description"])); ?>
                        </div>
                    </div>
                    <div class="col-lg-12 col-xlg-6 col-md-12">
                        <div class="card" id="<?php echo $row["id"]; ?>">
                            <div class="card-body">
                                <?php
                                if($_SESSION["rank"]== "admin") {
                                ?>
                                <div style='float: right;'><a href="/admin/manage_products?id=<?php echo $row["id"]; ?>"><i
                                                            class="bi bi-pencil-square"></i> Edit product</a></div>
                                <?php
                                }
                                ?>
                                <div style="display: flex;">
                                    <div>
                                        <img src="<?php echo $row['image']; ?>" class="rounded-circle"
                                            style="height: 100px;">
                                    </div>
                                    <div style="margin-left: 30px;">
                                        <form action="/payment?method=stripe" method="post">
                                            <input type="hidden" name="currency" value="<?php echo $currency ?>" />
                                            <input type="hidden" name="product_id" value="<?php echo $row['id'] ?>" />
                                            <input type="hidden" name="steamid"
                                                value="<?php echo $_SESSION["steamid"] ?>" />
                                            <input type="hidden" name="amount" value="<?php echo $row["amount"] ?>" />

                                            <h2><?php echo $row['name'] ?></h2>
                                            <strong>Price:
                                                <?php
                                            if($mydate[month] == "December" && date("d") < 1 || $mydate[month] == "October" && date("d") < 30) 
                                            {
                                            ?>
                                                <b
                                                    style="color: green;"><?php echo substr_replace(ltrim($row['amount'], '0'), ',', 1, 0); ?></b>
                                                <?php echo $currency ?>
                                                <?php
                                            }
                                            else
                                            {
                                            ?>
                                                <b
                                                    style="color: green;"><?php echo substr_replace(ltrim($row['amount'], '0'), ',', 1, 0); ?></b>
                                                <?php echo $currency ?>
                                            <?php
                                            }
                                            ?>
                                            </strong>
                                            <p><?php echo $row['head'] ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <?php if(!$_SESSION["steamid"]) { echo loginbutton('shop'); } else { ?>
                                <button class="btn btn-success text-white"><i class="fa fa-shopping-cart"
                                        aria-hidden="true"></i> Pay with debit or credit card</button>
                                </form>
                                <?php } if($row['show_more_btn'] == 1) { ?>
                                <a href="#<?php echo $row["id"]; ?>" class="btn btn-primary"><i
                                        class="bi bi-info-circle-fill"></i> Show kit</a>
                                <?php
                            }
                            ?>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
            <!-- Bootstrap tether Core JavaScript -->
            <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/js/app-style-switcher.js"></script>
            <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js"></script>
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