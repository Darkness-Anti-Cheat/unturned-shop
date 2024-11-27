<!DOCTYPE html>
<html dir="ltr" lang="en">
<?php 
use voku\helper\AntiXSS;
$website = "Manage users";
include "../layouts/head.php"; 
require "../controllers/discord.php";
require "../controllers/config.php";

$antiXss = new AntiXSS();
?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script>
function confirm() {
    Swal.fire({
        title: "Are you sure?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        showCancelButton: true,
        didOpen: () => {
            var audioplay = new Audio("../assets/mp3/pop.mp3");
            audioplay.play();
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('manage_users').submit();
        }
    });
}
</script>

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
                    if(isset($_POST["update"])) 
                    {
                        $steamid_update = filter_var($_POST["steamid"], FILTER_SANITIZE_STRING);
                        $discord_update = filter_var($_POST["discord"], FILTER_SANITIZE_STRING);
                        $status_update = filter_var($_POST["status"], FILTER_SANITIZE_STRING);
                        $steamidtransfer = filter_var($_POST["steamidtransfer"], FILTER_SANITIZE_STRING);

                        $ip_update = filter_var($_POST["ip"], FILTER_SANITIZE_STRING);
                        if(empty($steamidtransfer)) 
                        {
                            $queries->update("users", "
                            `id`=:id,
                            `steamid`=:steamid, 
                            `discord`=:discord, 
                            `ip`=:ip, 
                            `status`=:status", 
                            [ 
                                ':id' => filter_var($_GET["id"], FILTER_SANITIZE_STRING),
                                ':steamid' => $steamid_update, 
                                ':discord' => $discord_update,
                                ':ip' => $ip_update, 
                                ':status' => $status_update
                            ]);
                        }
                        else
                        {
                            $queries->update("users", "
                            `id`=:id,
                            `steamid`=:new_steamid", 
                            [ 
                                ':id' => filter_var($_GET["id"], FILTER_SANITIZE_STRING),
                                ':new_steamid' => $steamidtransfer
                            ]);

                            $queries->update("perks", "
                            `old_steamid`=:old_steamid,
                            `steamid`=:new_steamid", 
                            [ 
                                ':old_steamid' => $steamid_update,
                                ':new_steamid' => $steamidtransfer
                            ]);

                            $queries->update("itemvault_pei", "
                            `old_steamid`=:old_steamid,
                            `csteamid`=:new_steamid", 
                            [ 
                                ':old_steamid' => $steamid_update,
                                ':new_steamid' => $steamidtransfer
                            ]);

                            $queries->update("itemvault_washington", "
                            `old_steamid`=:old_steamid,
                            `csteamid`=:new_steamid", 
                            [ 
                                ':old_steamid' => $steamid_update,
                                ':new_steamid' => $steamidtransfer
                            ]);
                        }
                    }
                    
                    if(isset($_POST["delete"])) {
                        
                        $delete = $_POST["users_selected"];

                        foreach ($delete as $user) 
                        {
                            $queries->delete("users", [ ':id' => $user ]);
                        }           
                    }
                    ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php if(filter_var(empty($_GET["id"]), FILTER_SANITIZE_STRING)) { ?>
                        <div class="card">
                            <div class="card-body" style="overflow-x:auto;">
                                <div style="float: right">
                                    <label>Total Users: <?php 
                                    foreach($queries->custom_select("SELECT count(*) FROM users") as $row) 
                                    {
                                        echo "<b>" . $row[0] . "</b>"; 
                                    }
                                    ?> 
                                    Page: <b><?php echo filter_var(empty($_GET["page"]) || $_GET["page"] < 0  ? 1 : $_GET["page"], FILTER_SANITIZE_STRING); ?></b></label>
                                </div>
                                <div class="form-group">
                                    <label for="search_input">Find user</label>
                                    <input class="form-control" type="text" onkeyup="search_table()" name="search_input"
                                        id="search_input" placeholder="...">
                                </div>
                                <div class="form-group">
                                    <label for="findby">Find by</label>
                                    <select name="findby" id="findby" class="form-select shadow-none">
                                        <option value="2">id</option>
                                        <option value="3">steamid</option>
                                        <option value="4">discord</option>
                                        <option value="5">ip</option>
                                        <option value="6">rank</option>
                                        <option value="7">status</option>
                                    </select>
                                </div>
                                <table class="table" id="search">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Editor</th>
                                            <th scope="col">id</th>
                                            <th scope="col">steamid</th>
                                            <th scope="col">discord</th>
                                            <th scope="col">ip</th>
                                            <th scope="col">rank</th>
                                            <th scope="col">status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <form method="POST" id="manage_users">
                                            <?php
                                    //NOTE - Show all pages with limit confirm
                                    if($_GET["page"] == "all") 
                                    {
                                        $limit = filter_var($_GET["limit"], FILTER_SANITIZE_STRING);
                                        $SQLtable = $queries->select("users", "LIMIT $limit");
                                    }
                                    else
                                    {
                                        $page = filter_var(empty($_GET["page"]) || $_GET["page"] < 0  ? 1 : $_GET["page"], FILTER_SANITIZE_STRING);
                                        $pagina_actual = isset($page) ? $page : 1;
                                        $offset = ($pagina_actual - 1) * 10;
                                        $SQLtable = $queries->select("users", "LIMIT 10 OFFSET $offset");
                                    }

                                    foreach($SQLtable as $row) 
                                    {			                  
                                        //NOTE - API USAGE AND OPTIMIZATIONS
                                        if($_GET["page"] != "all") 
                                        {
                                            $json = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=" . STEAM_MANAGESERVERKEY . "&steamids=" . $row["steamid"]);
                                            $parsed = json_decode($json, true);
                                        }
                                    ?>
                                            <tr>
                                                <td><input type="checkbox" name="users_selected[]"
                                                        value="<?php echo $antiXss->xss_clean($row["id"]); ?>"></input></td>
                                                <td><a href="?id=<?php echo $antiXss->xss_clean($row["id"]); ?>" target="_blank"><i
                                                            class="bi bi-pencil-square"></i> Edit user</a></td>
                                                <td><?php echo $antiXss->xss_clean($row["id"]); ?></td>
                                                <td>
                                                    <?php 
                                                    if($_GET["page"] != "all") 
                                                    {
                                                    ?>
                                                    <img style="border-radius: 50%;" alt="user-img" src="<?php echo $parsed['response']['players'][0]['avatar']; ?>"></img> 
                                                    <?php 
                                                    }
                                                    ?>
                                                    <b><a href="https://steamcommunity.com/profiles/<?php echo $antiXss->xss_clean($row["steamid"]); ?>" target="_blank"><?php echo $antiXss->xss_clean($row["steamid"]); ?>
                                                    </a></b>
                                                </td>
                                                <td><b><?php echo $antiXss->xss_clean($row["discord"]); ?></b></td>
                                                <td><b><?php echo $antiXss->xss_clean(!empty($row["ip"]) ? $row["ip"] : "Not found"); ?> <img src="http://api.ip2flag.com/<?php echo !empty($row["ip"]) ? $row["ip"] : "8.8.8.8" ?>/16"/></b></td>
                                                <td><b><?php echo $antiXss->xss_clean($row["rank"]); ?></b></td>
                                                <td><?php echo $antiXss->xss_clean($row["status"] == 0 ? "<i class='bi bi-flag'></i><span> access</span>" : "<i class='bi bi-flag-fill'></i><span> disabled</span>"); ?></b></td>
                                                <input type="hidden" name="delete" value="" />
                                            </tr>
                                            <?php
                                    }
                                    ?>
                                        </form>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                <div style="float: left;">
                                    <button onclick="confirm()" id="delete" class="btn btn-danger text-white" disabled><i
                                            class="bi bi-person-dash-fill"></i> Delete</button>
                                </div>
                                <div style="float: right;">
                                    <a href="?page=<?php echo $_GET["page"] - 1; ?>" class="btn btn-dark text-white" <?php 
                                            if (filter_var(empty($_GET["page"]) || $_GET["page"] < 0 || $_GET["page"] == 1 || $_GET["page"] == "all")) {
                                                echo "hidden";
                                            }
                                            ?>><i
                                            class="bi bi-arrow-bar-left"></i> Previous</a>
                                    <button onclick="showall()" class="btn btn-info text-white"><i
                                            class="bi bi-exclamation-diamond-fill"></i> Show all</button>
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
                    <?php
                    }
                    else
                    {
                        //NOTE - Edit user section
                        $user_id = filter_var($_GET["id"], FILTER_SANITIZE_STRING);

                        //NOTE - Fetch all data
                        foreach($queries->select("users", "WHERE users.id=${user_id}") as $row) 
                        {		
                            $id_editing_user = $antiXss->xss_clean($row["id"]);
                            $steamid_editing_user = $antiXss->xss_clean($row["steamid"]);
                            $discordid_editing_user = $antiXss->xss_clean($row["discord"]);
                            $ip_editing_user = $antiXss->xss_clean($row["ip"]);
                            $status_editing_user = $antiXss->xss_clean($row["status"]);
                        }
                    ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <form method="POST">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="steamid">SteamID64</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        class="bi bi-steam"></i></span>
                                            </div>
                                            <input id="steamid" name="steamid" placeholder="SteamID64"
                                                value="<?php echo $steamid_editing_user; ?>" type="text"
                                                class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="steamidtransfer">SteamID64 Transfer <b>Data</b></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        class="bi bi-steam"></i></span>
                                            </div>
                                            <input id="steamidtransfer" name="steamidtransfer" placeholder="SteamID64" type="text"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="discord">Discord ID</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        class="bi bi-discord"></i></span>
                                            </div>
                                            <input id="discord" name="discord" placeholder="Discord ID"
                                                value="<?php echo $discordid_editing_user; ?>" type="text"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="ip">IP Address</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        class="bi bi-link-45deg"></i></span>
                                            </div>
                                            <input id="ip" name="ip" placeholder="..."
                                                value="<?php echo $ip_editing_user; ?>" type="text" class="form-control"
                                                disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="status" class="">Status</label>
                                        <div class="col-sm-12 border-bottom">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i
                                                            class="bi bi-ban"></i></span>
                                                </div>
                                                <select name="status" id="status" class="form-select shadow-none">
                                                    <?php if ($status_editing_user == 1) { ?>
                                                    <option value="1">banned</option>
                                                    <option value="0">active</option>
                                                    <?php } else { ?>
                                                    <option value="0">active</option>
                                                    <option value="1">banned</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div style="float: left;">
                                        <button type="submit" name="update" class="btn btn-info text-white"><i
                                                class="bi bi-arrow-clockwise"></i> Update</button>
                                    </div>
                                </div>
                            </div>
                    </div>
                    </form>
                    <?php 
                    } 
                    ?>
                </div>
            </div>
        </div>
        <script>
            $('input[type="checkbox"]').change(function() {
                let selected = false;
                $('input[type="checkbox"]').each(function() {
                    if ($(this).is(':checked')) {
                        selected = true;
                    }
                });
                $("#delete").prop('disabled', !selected);
            });
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