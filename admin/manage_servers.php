<!DOCTYPE html>
<html dir="ltr" lang="en">
<?php 
$website = "Manage servers";
include "../layouts/head.php"; 
use xPaw\SourceQuery\SourceQuery;
?>
<script type="text/javascript">
function confirm() 
{
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
            $('#manage_servers').submit();
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
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
                    {
                        $ip_update = filter_var($_POST["ip"], FILTER_SANITIZE_STRING);
                        $port_update = filter_var($_POST["port"], FILTER_SANITIZE_STRING);
                        $vote_link_update = filter_var($_POST["vote_link"], FILTER_SANITIZE_STRING);
                        $image_link_update = filter_var($_POST["image"], FILTER_SANITIZE_STRING);
                        $websocket_ip_update = filter_var($_POST["websocket_ip"], FILTER_SANITIZE_STRING);
                        $websocket_port_update = filter_var($_POST["websocket_port"], FILTER_SANITIZE_STRING);
                        $websocket_password_update = filter_var($_POST["websocket_password"], FILTER_SANITIZE_STRING);
                        $websocket_type_update = filter_var($_POST["websocket_type"], FILTER_SANITIZE_STRING);


                        if(isset($_POST["add"])) 
                        {
                            $queries->insert("servers", [
                                ':null' => null,
                                ':ip' => $ip_update, 
                                ':port' => $port_update, 
                                ':vote_link' => $vote_link_update, 
                                ':image' => empty($image_link_update) ? upload_file("../storage/", "filename") : $image_link_update,
                                ':websocket_ip' => $websocket_ip_update,
                                ':websocket_port' => $websocket_port_update,
                                ':websocket_password' => $websocket_password_update,
                                ':websocket_type' => $websocket_type_update
                            ]);
                        }

                        if(isset($_POST["update"])) 
                        {
                            $queries->update("servers", "
                            `id`=:id, 
                            `ip`=:ip, 
                            `port`=:port, 
                            `vote_link`=:vote_link, 
                            `image`=:image, 
                            `websocket_ip`=:websocket_ip, 
                            `websocket_port`=:websocket_port, 
                            `websocket_password`=:websocket_password, 
                            `websocket_type`=:websocket_type", 
                            [ 
                                ':id' => filter_var($_GET["id"], FILTER_SANITIZE_STRING),
                                ':ip' => $ip_update, 
                                ':port' => $port_update, 
                                ':vote_link' => $vote_link_update, 
                                ':image' => empty($image_link_update) ? upload_file("../storage/", "filename") : $image_link_update,
                                ':websocket_ip' => $websocket_ip_update,
                                ':websocket_port' => $websocket_port_update,
                                ':websocket_password' => $websocket_password_update,
                                ':websocket_type' => $websocket_type_update
                            ]);
                        }

                        if(isset($_POST["delete"])) {
                        
                            $delete = $_POST["servers_selected"];
    
                            foreach ($delete as $server) 
                            {
                                $queries->delete("servers", [ ':id' => $server ]);
                            }                
                        }
                    }
                    ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php if(filter_var(empty($_GET["id"]), FILTER_SANITIZE_STRING)) { ?>
                        <div class="card">
                            <div class="card-body" style="overflow-x:auto;">
                            <div style="float: right">
                                    <label>Total Servers: <?php 
                                    foreach($queries->custom_select("SELECT count(*) FROM servers") as $row) 
                                    {
                                        echo "<b>" . $row[0] . "</b>"; 
                                    }
                                    ?> 
                                    Page: <b><?php echo filter_var(empty($_GET["page"]) || $_GET["page"] < 0  ? 1 : $_GET["page"], FILTER_SANITIZE_STRING); ?></b></label>
                                </div>
                                <div class="form-group">
                                    <label for="search_input">Find server</label>
                                    <input class="form-control" type="text" onkeyup="search_table()" name="search_input"
                                        id="search_input" placeholder="...">
                                </div>
                                <div class="form-group">
                                    <label for="findby">Find by</label>
                                    <select name="findby" id="findby" class="form-select shadow-none">
                                        <option value="2">id</option>
                                        <option value="3">ip</option>
                                        <option value="4">port</option>
                                        <option value="5">vote_link</option>
                                        <option value="6">image</option>
                                    </select>
                                </div>
                                <table class="table" id="search">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Editor</th>
                                            <th scope="col">id</th>
                                            <th scope="col">ip</th>
                                            <th scope="col">port</th>
                                            <th scope="col">vote_link</th>
                                            <th scope="col">image</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <form method="POST" id="manage_servers">
                                            <?php
                                    //NOTE - Show all pages with limit confirm
                                    if($_GET["page"] == "all") 
                                    {
                                        $limit = filter_var($_GET["limit"], FILTER_SANITIZE_STRING);
                                        $SQLtable = $queries->select("servers", "LIMIT $limit");
                                    }
                                    else
                                    {
                                        $page = filter_var(empty($_GET["page"]) || $_GET["page"] < 0  ? 1 : $_GET["page"], FILTER_SANITIZE_STRING);
                                        $pagina_actual = isset($page) ? $page : 1;
                                        $offset = ($pagina_actual - 1) * 10;
                                        $SQLtable = $queries->select("servers", "LIMIT 10 OFFSET $offset");
                                    }

                                    foreach($SQLtable as $row) 
                                    {
                                    ?>
                                            <tr>
                                                <td><input type="checkbox" name="servers_selected[]"
                                                        value="<?php echo $antiXss->xss_clean($row["id"]); ?>"></input></td>
                                                <td><a href="?id=<?php echo $antiXss->xss_clean($row["id"]); ?>" target="_blank"><i
                                                            class="bi bi-pencil-square"></i> Edit server</a></td>
                                                <td><?php echo $antiXss->xss_clean($row["id"]); ?></td>
                                                <td><b><?php echo $antiXss->xss_clean($row["ip"]); ?></b></td>
                                                <td><b><?php echo $antiXss->xss_clean($row["port"]); ?></b></td>
                                                <td><b><a href="<?php echo $antiXss->xss_clean($row["vote_link"]); ?>"><?php echo $antiXss->xss_clean($row["vote_link"]); ?></a></b></td>
                                                <td><a id="image_preview" href="<?php echo $antiXss->xss_clean($row["image"]); ?>"><?php echo substr($row["image"], 0, 20) . "..."; ?></a></td>
                                                <td style="width: 180px;"><a class="btn btn-info text-white" onclick="test_connection(this)" websocket-type="<?php echo $antiXss->xss_clean($row["websocket_type"]); ?>" websocket-ip="<?php echo $antiXss->xss_clean($row["websocket_ip"]); ?>" websocket-port="<?php echo $antiXss->xss_clean($row["websocket_port"]); ?>"><i class="bi bi-arrow-repeat"></i> Check websocket</a></td>
                                                <!--<td style="width: 30px;"><a class="btn btn-success text-white">RCON</a></td>-->
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
                                    <a href="?id=add" class="btn btn-success text-white"><i
                                            class="bi bi-database-fill-add"></i> Add</a>
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
                                    <a href="?page=<?php echo $_GET["page"] + 1; ?>" class="btn btn-dark text-white" <?php 
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
                    $server_id = filter_var($_GET["id"], FILTER_SANITIZE_STRING);

                    if($server_id != "add") 
                    {
                        //NOTE - Fetch all data
                        foreach($queries->select("servers", "WHERE id=${server_id}") as $row) 
                        {		
                            $id_editing_server = $row["id"];
                            $ip_editing_server = $row["ip"];
                            $port_editing_server = $row["port"];
                            $vote_link_editing_server = $row["vote_link"];
                            $image_editing_server = $row["image"];
                            $websocket_ip_editing = $row["websocket_ip"];
                            $websocket_port_editing = $row["websocket_port"];
                            $websocket_password_editing = $row["websocket_password"];
                            $websocket_type_editing = $row["websocket_type"];
                        }
                    }
                    ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <form method="POST">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="ip">IP Address</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        class="bi bi-database"></i></span>
                                            </div>
                                            <input id="ip" name="ip" placeholder="0.0.0.0"
                                                value="<?php echo empty($ip_editing_server) ? "" : $ip_editing_server; ?>" type="text"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="port">Port</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        class="bi bi-database-add"></i></span>
                                            </div>
                                            <input id="port" name="port" placeholder="27015"
                                                value="<?php echo empty($port_editing_server) ? "" : $port_editing_server; ?>" type="text"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="vote_link">Vote link</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        class="bi bi-link-45deg"></i></span>
                                            </div>
                                            <input id="vote_link" name="vote_link"
                                                placeholder="https://unturned-servers.com/"
                                                value="<?php echo empty($vote_link_editing_server) ? "" : $vote_link_editing_server; ?>" type="text"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="image">Image</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                        class="bi bi-image-fill"></i></span>
                                            </div>
                                            <input id="image" name="image"
                                                placeholder="https://unturned-servers.com/image_map.png"
                                                value="<?php echo empty($image_editing_server) ? "" : $image_editing_server; ?>" type="text"
                                                class="form-control">
                                        </div>
                                        <input type="file" id="file" name="filename" accept="image/*">
                                    </div>
                                    <div class="form-group">
                                        <label for="websocket_ip">Websocket ip</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-broadcast-pin"></i></span>
                                            </div>
                                            <input id="websocket_ip" name="websocket_ip"
                                                placeholder="0.0.0.0"
                                                value="<?php echo empty($websocket_ip_editing) ? "" : $websocket_ip_editing; ?>" type="text"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="websocket_port">Websocket port</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-broadcast-pin"></i></span>
                                            </div>
                                            <input id="websocket_port" name="websocket_port"
                                                placeholder="8181"
                                                value="<?php echo empty($websocket_port_editing) ? "" : $websocket_port_editing; ?>" type="text"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="websocket_port">Websocket password</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-broadcast-pin"></i></span>
                                            </div>
                                            <input id="websocket_password" name="websocket_password"
                                                placeholder="Password"
                                                value="<?php echo empty($websocket_password_editing) ? "" : $websocket_password_editing; ?>" data-secret="secret" type="password"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="websocket_type">Websocket type</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="bi bi-lock-fill"></i></span>
                                            </div>
                                            <select name="websocket_type" id="websocket_type" class="form-select shadow-none">
                                                <?php if($websocket_type_editing == "ws") { ?>
                                                <option value="ws">NO SSL</option>
                                                <option value="wss">SSL</option>
                                                <?php } else { ?>
                                                <option value="wss">SSL</option>
                                                <option value="ws">NO SSL</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div style="float: left;">
                                        <?php if($server_id == "add") { ?>
                                        <button type="submit" name="add" class="btn btn-success text-white"><i
                                                class="bi bi-database-fill-add"></i> Add</button>
                                        <?php } else { ?>
                                        <button type="submit" name="update" class="btn btn-info text-white"><i
                                                class="bi bi-arrow-clockwise"></i> Update</button>
                                        <a onclick="show_secret()" class="btn btn-danger text-white"><i class="bi bi-incognito"></i> Show secret</a>
                                        <?php } ?>
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
            function show_secret() {
                var inputElements = document.getElementsByTagName("input");

                for (var i = 0; i < inputElements.length; i++) 
                {
                    var currentInput = inputElements[i];

                    if (currentInput.type === "password" && currentInput.dataset.secret) 
                    {
                        currentInput.type = "text";
                    } 
                    else if (currentInput.type === "text" && currentInput.dataset.secret) 
                    {
                        currentInput.type = "password";
                    }
                }
            }
            $('a#image_preview').miniPreview({ 
                prefetch: 'none',
                height: "50",
                width: "50"
            });

            $('input[type="checkbox"]').change(function() {
                let selected = false;
                checkboxes_selected = [];
                $('input[type="checkbox"]').each(function() {
                    if ($(this).is(':checked')) {
                        checkboxes_selected.push($(this).val());
                        selected = true;
                    }
                });
                $('#delete').prop('disabled', !selected);
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