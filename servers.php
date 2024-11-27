<!DOCTYPE html>
<html dir="ltr" lang="en">
<?php 
$website = "Servers";
include "layouts/head.php"; 
use xPaw\SourceQuery\SourceQuery;
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
                    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px;margin-bottom:10px;"
                        data-ad-client="ca-pub-3186822807904753" data-ad-slot="9390044227"></ins>
                    <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </div>
                <div class="row justify-content-left" id="card-container">
                        <?php 
                        foreach($queries->select("servers", "ORDER BY `id` ASC") as $row) 
                        {
                        ?>
                        <div class="col-lg-12 col-xlg-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div style="float: left;"><?php echo "Server <b>" . $row["id"] . "</b>";  ?></div>
                                <div class="movable" style="float: right;"><i class="bi bi-arrows-move"></i></div>
                            </div>
                            <div class="card-body">
                                <?php
                                if($_SESSION["rank"]== "admin") {
                                ?>
                                <div style='float: right;'><a href="/admin/manage_servers?id=<?php echo $row["id"]; ?>"><i
                                                            class="bi bi-pencil-square"></i> Edit server</a></div>
                                <?php
                                }
                                ?>
                                <div style="display: flex;">
                                    <div style="float: left;">
                                        <img src="<?php echo $row["image"]; ?>" style="height: 100px; border-radius: 15px;">
                                    </div>
                                    <div style="margin-left: 10px; float: left;">
                                    <?php     
                                    try {
                                        $Query = new SourceQuery( );
                                    
                                        $Query->Connect( $row["ip"] , $row["port"], 1, SourceQuery::SOURCE );
                                        if(!empty($Query->GetInfo()) || $Query->GetInfo() != "Failed to read any data from socket") 
                                        {
                                            echo "<p>" . $Query->GetInfo( )["HostName"] . " <span class='badge bg-success rounded'>online</span></p>";
                                            echo $Query->GetInfo( )["ModDesc"] . "</br>";
                                            echo "<p>" . $Query->GetInfo( )["Players"] . "/" . $Query->GetInfo( )["MaxPlayers"] . " <b>players</b></p>";
                                        }
                                        else
                                        {
                                            echo "<div style='float: right;'><span class='badge bg-danger rounded'>offline</span></div>";
                                        } 
                                        $Query->Disconnect( );
                                    }    
                                    catch(Exception $ex) {
                                        echo "<div style='float: right;'><span class='badge bg-danger rounded'>offline</span></div>";
                                    }            
                                    ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a class="btn btn-success text-white" href="<?php echo $row['vote_link'] ?>"><i
                                        class="bi bi-hand-thumbs-up"></i> Vote</a>
                                <a class="btn btn-info text-white"
                                    href="steam://connect/<?php echo $row['ip'] . ":" . $row['port']; ?>"><i
                                        class="bi bi-box-arrow-in-right"></i> Connect to the server</a>
                                <!--<a class="btn btn-dark" style="float: right;" href="#<?php //echo $row['ip'] . ":" . $row['port']; ?>"><i class="bi bi-three-dots"></i> Show players</a>-->
                            </div>
                        </div>
                        </div>
                        <?php
                        }
                        ?>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/plugins/bower_components/jquery/dist/jquery.min.js"></script>
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
    <script
        src="<?php echo DOMAIN_WEBPAGE; ?>assets/plugins/bower_components/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js">
    </script>
    <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/js/pages/dashboards/dashboard1.js"></script>
</body>

</html>