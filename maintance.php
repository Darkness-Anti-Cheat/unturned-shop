<?php
header("refresh:10s;url=index");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintance</title>
    <style type="text/css">
    @font-face {
        font-family: 'Changa-One';
        src: url('assets/plugins/fonts/Changa-One.ttf.woff') format('woff'),
            url('assets/plugins/fonts/Changa-One.ttf.svg#Changa-One') format('svg'),
            url('assets/plugins/fonts/Changa-One.ttf.eot'),
            url('assets/plugins/fonts/Changa-One.ttf.eot?#iefix') format('embedded-opentype');
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
        /* Centra el contenido del div */
        padding: 20px;
        /* Agrega relleno al contenido */
    }

    body {
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: black;
        color: white;
        /* Asegura que el contenedor ocupe al menos la altura completa de la ventana */
    }
    </style>
</head>
<body>
    <div class="center">
        <h1>Maintance</h1>
        <p class="sansserif">Please comeback in some minutes...</p>
    </div>
</body>
</html>