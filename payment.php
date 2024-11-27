<!DOCTYPE html>
<html dir="ltr" lang="en">

<?php 
$website = "Payment";
include "layouts/head.php"; 
include "controllers/config.php";

require_once 'controllers/vendor/autoload.php';

if(!isset($_POST["product_id"])) 
{
    header("Refresh: 1; URL=index");
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
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <?php include "layouts/side.php"; ?>
        <?php if (!isset($_SESSION['steamid'])) { header('location: index'); } ?>
        <div class="page-wrapper">
            <div class="page-breadcrumb bg-black text-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title"><?php echo $website; ?></h4>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12">
                    <form action="services/checkout" method="post" id="payment-form">
                        <div class="card">
                            <div class="card-header"><img src="assets/plugins/images/stripe.png" height="30"></div>
                            <input type="hidden" name="currency" value="<?php echo $_POST["currency"]; ?>" />
                            <input type="hidden" name="product_id" value="<?php echo $_POST["product_id"]; ?>" />
                            <input type="hidden" name="steamid" value="<?php echo $_SESSION["steamid"]; ?>" />
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="cardholder-name">Your <b>credit card</b> name and surname</label>
                                    <input id="cardholder-name" name="cardholder-name"
                                        placeholder="Example: Alexander Cortes Fernandez" type="text"
                                        class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Your <b>email</b></label>
                                    <input id="email" name="email" placeholder="Email: example@darknesscommunity.club"
                                        type="email" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="card-element">Your <b>credit card</b> credentials</label>
                                    <div id="card-element" class="form-control"></div>
                                </div>
                                <div class="form-group">
                                    <label class="custom-control custom-checkbox">
                                        <input class="custom-control-input" checked id="agree" name="agree"
                                            type="checkbox"><span class="custom-control-label"> I accept <a
                                                href="terms" target="_blank"><b>
                                                    Terms and Conditions</b></a></span>
                                </div>
                                <button id="card-button" data-secret="<?= $intent->client_secret ?>"
                                    class="btn btn-dark"><i class="bi bi-credit-card-2-back-fill"></i> Pay now
                                    with credit or debit card</button> <b>Total Price</b>
                                <?php echo substr_replace(ltrim($_POST["amount"], '0'), ',', 1, 0) . " " . $_POST["currency"]; ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <script>
            var stripe = Stripe('<?php echo PRIVATE_KEY ?>');
            var elements = stripe.elements();
            var cardholderName = document.getElementById('cardholder-name');
            var email = document.getElementById('email');
            var checkbox = document.getElementById('agree');
            
            var card = elements.create('card');

            card.mount('#card-element');

            var form = document.getElementById('payment-form');
            form.addEventListener('submit', function(event) 
            {
                event.preventDefault();

                if(checkbox.checked) 
                {
                    stripe.createToken(card, {
                        billing_details: {
                            name: cardholderName.value,
                            email: email.value
                        },
                        address: {
                            name: cardholderName.value
                        }
                    }).then(function(result) 
                    {
                        if (result.error) 
                        {
                            Swal.fire({
                                title: 'Error',
                                text: result.error.message,
                                icon: 'warning',
                                didOpen: () => {
                                    var audioplay = new Audio("../assets/mp3/pop.mp3");
                                    audioplay.play();
                                }
                            });
                        } 
                        else 
                        {
                            stripe_token_handler(result.token);
                        }
                    });
                }
                else
                {
                    Swal.fire({
                        title: 'Please',
                        text: 'You need to accept terms and conditions',
                        icon: 'info',
                        didOpen: () => {
                            var audioplay = new Audio("../assets/mp3/pop.mp3");
                            audioplay.play();
                        }
                    });
                }
            });

            function stripe_token_handler(token) {

                var form = document.getElementById('payment-form');
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token.id);
                form.appendChild(hiddenInput);

                form.submit();
            }
            </script>
            <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/plugins/bower_components/jquery/dist/jquery.min.js">
            </script>
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
            <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/plugins/bower_components/chartist/dist/chartist.min.js">
            </script>
            <script
                src="<?php echo DOMAIN_WEBPAGE; ?>assets/plugins/bower_components/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js">
            </script>
            <script src="<?php echo DOMAIN_WEBPAGE; ?>assets/js/pages/dashboards/dashboard1.js"></script>
</body>

</html>