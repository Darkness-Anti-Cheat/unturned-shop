<!DOCTYPE html>
<html dir="ltr" lang="en">
<?php 
$website = "Manage graphic";
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
                        header("Refresh:0");
                    }
                    ?>
                        <div class="col-lg-12 col-xlg-12 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div style="float: left;">Payments <b>graphic</b></div>
                                    <div class="movable" style="float: right;"><i class="bi bi-arrows-move"></i></div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group" id="payment_Graphic"></div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            </form>
        </div>
        <script src="../assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
        <!-- Bootstrap tether Core JavaScript -->
        <script src="../assets/js/app-style-switcher.js"></script>
        <!--Wave Effects -->
        <script src="../assets/js/waves.js"></script>
        <!--Menu sidebar -->
        <script src="../assets/js/sidebarmenu.js"></script>
        <!--Custom JavaScript -->
        <script src="../assets/js/custom.js"></script>
        <script src="https://unpkg.com/lightweight-charts/dist/lightweight-charts.standalone.production.js"></script>
        <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/js/drawChart.js"></script>
        <script>
        // Obtenemos la consulta entera dentro de este json
        let payments = 
            <?php
            $SQLtable = $queries->custom_select("SELECT YEAR(payments.date) AS year,
                                                       MONTH(payments.date) AS month,
                                                       COUNT(*) AS value
                                                FROM payments
                                                INNER JOIN products ON products.id = payments.product_id
                                                GROUP BY YEAR(payments.date), MONTH(payments.date)
                                                ORDER BY year ASC, month ASC");
            
            $results = $SQLtable->fetchAll(PDO::FETCH_ASSOC);

            $jsonResults = json_encode($results);

            echo $jsonResults;
            ?>

        const chartData = payments.map(item => ({
            time: new Date(item.year, item.month - 1).getTime() / 1000,
            value: item.value === null ? 0 : item.value
        }));

        drawChart("payment_Graphic", chartData, "Payments")
        </script>
</body>

</html>
