<?php
require '../controllers/vendor/autoload.php';
include "../controllers/classes/queries.php";
require_once "../controllers/config.php";
use WebSocket\Client;

\Stripe\Stripe::setApiKey(PUBLIC_KEY);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing payment...</title>
    <style type="text/css">
    @font-face {
        font-family: 'Changa-One';
        src: url('../assets/plugins/fonts/Changa-One.ttf.woff') format('woff'),
            url('../assets/plugins/fonts/Changa-One.ttf.svg#Changa-One') format('svg'),
            url('../assets/plugins/fonts/Changa-One.ttf.eot'),
            url('../assets/plugins/fonts/Changa-One.ttf.eot?#iefix') format('embedded-opentype');
        font-weight: normal;
        font-style: normal;
    }

    h1 {
        font-family: 'Changa-One';
    }

    .sansserif {
        font-family: Arial, Helvetica, sans-serif;
    }

    .center {
        text-align: center;
        padding: 20px;
    }

    body {
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    </style>
</head>

<body>
    <div class="center">
        <img src="../assets/plugins/images/stripe.png" weight="200" height="200" alt="stripe-icon">
        <br>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') 
        {
            function test_connection($ip, $port) {
                try {
                    $client = new Client("ws://" . $ip . ":" . $port);
                
                    $client->send("test" . ":" . "test");
                    $client->close();
                    return 'OK';
                }
                catch (Exception $e) {
                    return 'FAILED';
                }
            }
        // Obtener el token del formulario
        $token = $_POST['stripeToken'];

        try 
        {
            foreach($queries->select("products", " WHERE `id`=" . filter_var($_POST["product_id"], FILTER_SANITIZE_STRING)) as $row) 
            {
                $product_id = $row["id"];
                $amount = $row["amount"];
                $name = $row["name"];
                $rank = $row["rank"];
                $associated_servers = [ $row["associated_servers"] ];
            }

            foreach($queries->select("users", " WHERE `steamid`=". filter_var($_POST["steamid"], FILTER_SANITIZE_STRING)) as $row) 
            {
                $user_id = $row["id"];
            }

            $charge = \Stripe\Charge::create([
                'amount' => $amount,
                'currency' => $_POST["currency"],
                'description' => $name,
                'receipt_email' => $_POST["email"],
                'source' => $token,
            ]);

            echo "<img src='../assets/plugins/images/loading.gif' weight='200' height='200' alt='loading-icon'>
            <h1 class='Changa-One'>Processing payment..</h1>
            <p class='sansserif' size='1'>don't close this until the payment is processed</p>";

            $queries->insert("payments", [
                ':null' => null,
                ':user' => $user_id, 
                ':email' => filter_var($_POST["email"], FILTER_SANITIZE_STRING), 
                ':product_id' => $product_id, 
                ':user_ip' => $_SERVER['REMOTE_ADDR'], 
                ':payment_token' => $charge->id, 
                ':date' => date('Y-m-d H:i:s')
            ]);

            echo 'Payment completed: ' . $charge->id;

            $ranks_separated = explode(",", $rank);

            foreach ($ranks_separated as $rank_element) 
            {
                $SQLservers = $queries->custom_select("SELECT * FROM `servers`");
                while($row = $SQLservers ->fetch()) 
                {
                    if(in_array($row["id"], $associated_servers)) {
                        if(test_connection($row["websocket_ip"], $row["websocket_port"]) === "OK") 
                        {
                            $client = new Client("ws://" . $row["websocket_ip"] . ":" . $row["websocket_port"]);
                            $client->send("payment" . ":" . $_POST["steamid"] . ":" . $rank_element . ":" . $row["websocket_password"]);
                            $client->close();
                        }
                    }
                }
            }

            header("Refresh: 3; URL=../account");
        } catch (\Stripe\Exception\CardException $e) {
            echo 'Error: ' . $e->getMessage();
        } catch (\Stripe\Exception\StripeException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    else
    {
        echo "<img src='../assets/plugins/images/error.png' weight='200' height='200' alt='error icon'>
                <h1 class='Changa-One'>Error redirecting in 3 seconds...</h1>
                <p class='sansserif' size='1'>Mmmm, ¡let's back to the index!</p>";
        header("Refresh: 3; URL=../index");
    }
    ?>
    </div>
</body>

</html>