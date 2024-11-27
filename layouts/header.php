<header class="topbar" data-navbarbg="skin5">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <div class="navbar-header" data-logobg="skin6">
            <a class="navbar-brand" href="../index" style="text-align: center;">
                <span class="logo-text">
                    <div style="margin-left: 10px;">
                        <h1 style="font-family: 'Font_custom', sans-serif;"><?php echo TITLE; ?></h1>
                    </div>
                </span>
            </a>
            <?php 
            if(isset($_SESSION['steamid']))
            {
                $steamid = $_SESSION['steamid'];
                $json = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=7F72BC8EA2ED8E9A6929792271D506A5&steamids=${steamid}");
                $parsed = json_decode($json, true);

                $queries->update("users", "
                `steamid`=:steamid, 
                `ip`=:ip", 
                [ 
                    ':steamid' => $steamid,
                    ':ip' => get_client_ip()
                ], false);

                if(is_phone()) 
                { 
            ?>
            <div style="margin-bottom: 3px; width: 300px;">
                <a class="profile-pic" href="../account">
                    <img src="<?php echo $parsed['response']['players'][0]['avatar']; ?>" alt="user-img"
                        width="36" class="img-circle" style="border: 2px solid white; border-radius: 100%;">
                    <span class="text-white font-medium"><?php echo $_SESSION['steam_personaname']; ?></span>
                </a>
                <a href="../logout" style="margin-left: 10px;">
                    <span class="text-white">logout</span>
                </a>
            </div>
            <?php 
                }    
            }
            else
            {
                if(is_phone()) 
                {
            ?>
            <div style="margin-bottom: 10px; margin-right: 10px;">
                <?php echo loginbutton('new'); ?>
            </div>
            <?php
                }
            }
            ?>
            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                    class="ti-menu ti-close"></i></a>
        </div>
        <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
            <ul class="navbar-nav ms-auto d-flex align-items-center">
                <?php
                    if(isset($_SESSION['steamid']))
                    {
                        foreach($queries->select("users", "WHERE steamid=" . $_SESSION['steamid']) as $row) 
                        {		
                            $_SESSION["rank"] = $row["rank"];
                            $_SESSION["status"] = $row["status"];
                        }

                        if($_SESSION["status"] === 1) {
                            die("You are banned");
                        }
                ?>
                <li>
                    <div style="margin-bottom: 3px; margin-right: 10px;">
                        <a class="profile-pic" href="../account">
                            <img src="<?php echo $parsed['response']['players'][0]['avatar']; ?>" alt="user-img"
                                width="36" class="img-circle" style="border: 2px solid white;">
                            <span class="text-white font-medium"><?php echo $_SESSION['steam_personaname']; ?></span>
                        </a>
                        <a href="../logout">
                            <span class="text-white">logout</span>
                        </a>
                    </div>
                </li>
                <?php
                                    }
                                    else
                                    {
                                ?>
                <li class="in">
                    <div style="margin-bottom: 10px; margin-right: 10px;">
                        <?php echo loginbutton('new'); ?>
                    </div>
                </li>
                <?php
                                    }
                ?>
            </ul>
        </div>
    </nav>
</header>
<div id="btn-mode" class="btn-mode">Dark</div>