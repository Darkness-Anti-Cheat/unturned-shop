<!DOCTYPE html>
<html dir="ltr" lang="en">
<?php
use voku\helper\AntiXSS;

$website = "Terms and conditions";
include "layouts/head.php";
include __DIR__ . '/controllers/vendor/autoload.php';
$Parsedown = new Parsedown();
$antiXss = new AntiXSS();
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
                    <?php
                    if(isset($_POST["update"]) && isset($_SESSION['steamid']) && $_SESSION["rank"] == "admin") 
                    {
                        file_put_contents("json/terms.md", $Parsedown->text($_POST["terms"]));
                        echo "<div class='alert alert-success' role='alert'><b>Terms</b> has been updated...</div>";
                    }
                    ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <form method="POST">
                        <div class="card">
                            <div class="card-body">
                                <?php if (isset($_SESSION['steamid']) && $_SESSION["rank"] == "admin") { ?>
                                <div class="form-group" style="margin-bottom: 45px;">
                                    <div style="float: right;">
                                        <a href="?mode=edit"><i class="bi bi-pencil-square"></i> Edit terms and conditions</a>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php
                                if(!empty($_GET["mode"]) && $_GET["mode"] == "edit" && isset($_SESSION['steamid']) && $_SESSION["rank"] == "admin") {
                                ?>
                                <div class="form-group">
                                    <div style="border: 1px solid black; padding: 5px;" id="markdown_preview"></div>
                                </div>
                                <div class="form-group">
                                    <label for="terms">Terms <b>(Markdown supported)</b> <a href="https://www.markdownguide.org/basic-syntax/">guide of markdown</a></label>
                                    <textarea id="terms" name="terms" placeholder="terms"
                                        class="form-control" style="height: 300px;"><?php echo $Parsedown->text(file_get_contents("json/terms.md")); ?></textarea>
                                </div>
                                <?php
                                } else {
                                ?>
                                <?php
                                    echo $antiXss->xss_clean($Parsedown->text(file_get_contents("json/terms.md")));
                                }
                                ?>
                            </div>
                            <?php
                            if($_GET["mode"] == "edit" && isset($_SESSION['steamid']) && $_SESSION['rank'] == "admin") {
                            ?>
                            <div class="card-footer">
                                <button type="submit" name="update" class="btn btn-info text-white"><i class="bi bi-arrow-clockwise"></i> Update</button>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.onload = function() 
        { 
            btn_mode();

            var easyMDE = new EasyMDE({ toolbar: [
                'bold', 'italic', 'strikethrough',
                'heading', 'quote', 'code',
                'unordered-list', 'ordered-list',
                'link', 'image', 'horizontal-rule',
                'undo', 'redo',
            ], spellChecker: false, sideBySideFullscreen: false, element: document.getElementById('terms') });

            easyMDE.codemirror.on("change", () => {
                document.getElementById('markdown_preview').innerHTML = easyMDE.value();
            });

            document.getElementById('markdown_preview').innerHTML = $('#terms').val();
        };
    </script>
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