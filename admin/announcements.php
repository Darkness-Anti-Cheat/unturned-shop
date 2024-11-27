<!DOCTYPE html>
<html dir="ltr" lang="en">
<?php 
$website = "Announcements";
include "../layouts/head.php"; 
require "../controllers/discord.php";
require "../controllers/config.php";

include "../controllers/vendor/autoload.php";
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
                <div class="row">
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
                    {
                        $title = filter_var($_POST["title"], FILTER_SANITIZE_STRING);
                        $description = filter_var($_POST["description"], FILTER_SANITIZE_STRING);
                        $color = filter_var($_POST["color"], FILTER_SANITIZE_STRING);
                        $author = filter_var($_SESSION['steam_personaname'], FILTER_SANITIZE_STRING);
                        $author_image = filter_var($parsed['response']['players'][0]['avatar'], FILTER_SANITIZE_STRING);

                        if(isset($_POST["send"])) 
                        {
                            webhook($color, $description, $title, $author, $author_image, WEBHOOK);
                            echo "<div class='alert alert-success' role='alert'><b>Announcement</b> has been sended succesfully...</div>";
                        }
                    }
                    ?>
                    <form method="POST">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div id="widget" style="position: fixed; height: 404px; background-color: black; pointer-events: none;">_</div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="color">Color</label>
                                    <br>
                                    <input name="color" id="color" type="color" id="colorpicker" name="colorpicker"/>
                                </div>
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-type-h1"></i></span>
                                        </div>
                                        <input id="title" name="title" placeholder="Hello world" type="text" class="form-control">                                        
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <br>
                                    <textarea id="description" name="description" placeholder="Description"
                                                class="form-control" style="height: 150px;"><?php echo $description_editing; ?></textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div style="float: left;">
                                    <button type="submit" name="send" class="btn btn-success text-white"><i class="bi bi-send-fill"></i> Send</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            $('a#image_preview').miniPreview({ 
                prefetch: 'none',
                height: "50",
                width: "50"
            });

            document.getElementById("color").onchange = function ()
            {
                var widget = document.getElementById("widget");

                widget.style.color = document.getElementById("color").value;
                widget.style.backgroundColor = document.getElementById("color").value;
            };
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