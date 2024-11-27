<!DOCTYPE html>
<html dir="ltr" lang="en">
<?php 
use voku\helper\AntiXSS;
$website = "Manage products";
include "../layouts/head.php"; 
$Parsedown = new Parsedown();
$antiXss = new AntiXSS();
?>
<script>
function confirm() 
{
    Swal.fire({
        title: "Are you sure you want to delete this product?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        showCancelButton: true,
        didOpen: () => {
            var audioplay = new Audio("../assets/mp3/pop.mp3");
            audioplay.play();
        }
    }).then((result) => {
        if (result.isConfirmed) 
        {
            $('#delete').click();
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
                <?php
                //NOTE - Check if request its a post
                if ($_SERVER['REQUEST_METHOD'] === 'POST') 
                {
                    $rank_update = filter_var($_POST["rank"], FILTER_SANITIZE_STRING);
                    $associated_servers_update = $_POST["servers_selected"];
                    $name_update = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
                    $description_update = $_POST["description"];
                    $head_update = filter_var($_POST["head"], FILTER_SANITIZE_STRING);
                    $amount_update = filter_var($_POST["amount"], FILTER_SANITIZE_STRING);
                    $image_update = filter_var($_POST["image"], FILTER_SANITIZE_STRING);
                    $show_more_btn_update = filter_var($_POST["show_more_btn"], FILTER_SANITIZE_STRING);
                    $deleted_update = filter_var($_POST["deleted"], FILTER_SANITIZE_STRING);

                    if(isset($_POST["delete"])) //NOTE - Delete product
                    {
                        $queries->delete("products", [ ':id' => filter_var($_GET["id"], FILTER_SANITIZE_STRING) ]);
                        header("Location: manage_products");
                    }

                    if(isset($_POST["add"])) //NOTE - Add product
                    {
                        $queries->insert("products", [
                            ':null' => null,
                            ':rank' => $rank_update,
                            ':associated_servers' => $associated_servers_update,
                            ':name' => $name_update, 
                            ':description' => $Parsedown->text($description_update), 
                            ':head' => $head_update, 
                            ':amount' => $amount_update, 
                            ':image' => empty($image_update) ? upload_file("../storage/", "filename") : $image_update, 
                            ':show_more_btn' => $show_more_btn_update, 
                            ':deleted' => $deleted_update
                        ]);
                    }

                    if(isset($_POST["update"])) //NOTE - Modify product
                    {
                        $queries->update("products", "
                        `id`=:id,
                        `rank`=:rank, 
                        `associated_servers`=:associated_servers, 
                        `name`=:name,
                        `description`=:description, 
                        `head`=:head, 
                        `amount`=:amount, 
                        `image`=:image,
                        `show_more_btn`=:show_more_btn,
                        `deleted`=:deleted", 
                        [ 
                            ':id' => filter_var($_GET["id"], FILTER_SANITIZE_STRING),
                            ':rank' => $rank_update, 
                            ':associated_servers' => $associated_servers_update,
                            ':name' => $name_update, 
                            ':description' => $Parsedown->text($description_update), 
                            ':head' => $head_update, 
                            ':amount' => $amount_update, 
                            ':image' => empty($image_update) ? upload_file("../storage/", "filename") : $image_update,
                            ':show_more_btn' => $show_more_btn_update, 
                            ':deleted' => $deleted_update
                        ]);
                    }          
                }
            ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php if(filter_var(empty($_GET["id"]), FILTER_SANITIZE_STRING)) { ?>
                        <div class="card" style="overflow-x:auto;">
                            <div class="card-body">
                            <div style="float: right">
                                    <label>Total Products: <?php 
                                    foreach($queries->custom_select("SELECT count(*) FROM products") as $row) 
                                    {
                                        echo "<b>" . $row[0] . "</b>"; 
                                    }
                                    ?> 
                                    Page: <b><?php echo filter_var(empty($_GET["page"]) || $_GET["page"] < 0  ? 1 : $_GET["page"], FILTER_SANITIZE_STRING); ?></b></label>
                                </div>
                                <div class="form-group">
                                    <label for="steamid">Find product</label>
                                    <input class="form-control" type="text" onkeyup="search_table()" name="search_input"
                                        id="search_input" placeholder="...">
                                </div>
                                <div class="form-group">
                                    <label for="findby">Find by</label>
                                    <select name="findby" id="findby" class="form-select shadow-none">
                                        <option value="0">id</option>
                                        <option value="2">name</option>
                                        <option value="3">rank</option>
                                        <option value="4">show more btn</option>
                                        <option value="5">logic deleted</option>
                                    </select>
                                </div>
                                <table class="table" id="search">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Editor</th>
                                            <th scope="col">name</th>
                                            <th scope="col">rank</th>
                                            <th scope="col">show_more_btn</th>
                                            <th scope="col">logic deleted</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    //NOTE - Show all pages with limit confirm
                                    if($_GET["page"] == "all") 
                                    {
                                        $limit = filter_var($_GET["limit"], FILTER_SANITIZE_STRING);
                                        $SQLtable = $queries->select("products", "LIMIT $limit");
                                    }
                                    else
                                    {
                                        $page = filter_var(empty($_GET["page"]) || $_GET["page"] < 0  ? 1 : $_GET["page"], FILTER_SANITIZE_STRING);
                                        $pagina_actual = isset($page) ? $page : 1;
                                        $offset = ($pagina_actual - 1) * 10;
                                        $SQLtable = $queries->select("products", "LIMIT 10 OFFSET $offset");
                                    }

                                    foreach($SQLtable as $row) 
                                    {		                  
                                    ?>
                                        <form method="POST">
                                            <tr>
                                                <td><?php echo $row["id"]; ?></td>
                                                <td><a href="manage_products?id=<?php echo $antiXss->xss_clean($row["id"]); ?>" target="_blank"><i class="bi bi-pencil-square"></i> Edit
                                                        product</a></td>
                                                <td><img style="border-radius: 50%; height: 30px;" src="<?php echo $antiXss->xss_clean($row["image"]); ?>"></img> <a id="image_preview" href="<?php echo $antiXss->xss_clean($row["image"]); ?>" style="text-decoration: none; color: black; cursor: default;"><?php echo $row["name"] ?></a></td>
                                                <td><b><?php echo $antiXss->xss_clean($row["rank"]); ?></b></td>
                                                <td><b><?php echo $antiXss->xss_clean($row["show_more_btn"]) == "0" ? "<i class='bi bi-eye-slash-fill'></i> hidden" : "<i class='bi bi-eye'></i> visible"; ?></b></td>
                                                <td><b><?php echo $antiXss->xss_clean($row["deleted"]) == "0" ? "<i class='bi bi-check'></i> not deleted" : "<i class='bi bi-trash-fill'></i> logic delete"; ?></b></td>
                                            </tr>
                                            <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            </form>
                            <div class="card-footer">
                                <div style="float: left;">
                                    <a href="?page=<?php echo $_GET["page"] - 1; ?>" class="btn btn-dark text-white" <?php 
                                            if (filter_var(empty($_GET["page"]) || $_GET["page"] < 0 || $_GET["page"] == 1 || $_GET["page"] == "all")) {
                                                echo "hidden";
                                            }
                                            ?>><i
                                            class="bi bi-arrow-bar-left"></i> Previous</a>
                                    <button onclick="showall()" class="btn btn-info text-white"><i
                                            class="bi bi-exclamation-diamond-fill"></i> Show all</button>
                                    <a href="?id=add" class="btn btn-success text-white"><i class="bi bi-cart-plus"></i>
                                        Add</a>
                                </div>
                                <div style="float: right;">
                                        <a href="?page=<?php echo empty($_GET["page"]) ? 2 : $_GET["page"] + 1; ?>" class="btn btn-dark text-white" <?php 
                                            if (filter_var($_GET["page"] == "all")) {
                                                echo "hidden";
                                            }
                                            ?>><i
                                            class="bi bi-arrow-bar-right"></i> Next</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    else
                    {
                        //NOTE - Check if is modify or add
                        if(filter_var($_GET["id"], FILTER_SANITIZE_STRING) != "add") 
                        {
                            $product_id = filter_var($_GET["id"], FILTER_SANITIZE_STRING);

                            foreach($queries->select("products", "WHERE id=${product_id}") as $row) 
                            {	
                                $id_editing_product = $row["id"];
                                $rank_editing = $row["rank"];
                                $servers_editing = $row["associated_servers"];
                                $name_editing= $row["name"];
                                $description_editing = $row["description"];
                                $head_editing = $row["head"];
                                $amount_editing = $row["amount"];
                                $image_editing = $row["image"];
                                $show_more_btn_editing = $row["show_more_btn"];
                                $deleted_editing = $row["deleted_editing"];
                            }
                        }
                    ?>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <form method="POST" id="manage_products" enctype="multipart/form-data">
                                <div class="card">
                                    <div class="card-body" style="overflow-x:auto;">
                                        <div class="form-group">
                                            <label for="rank_editing">Rank <b>(Permission group name)</b></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i
                                                            class="bi bi-award-fill"></i></span>
                                                </div>
                                                <input id="rank_editing" name="rank" placeholder="Rank permission"
                                                    value="<?php echo empty($rank_editing) ? "" : $rank_editing; ?>" type="text"
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="servers">Servers <b>(Product associated with the server)</b></label>
                                            <select id="servers" name="servers" data-placeholder="Select servers" multiple data-multi-select></select>
                                            <input type="hidden" id="servers_selected" name="servers_selected" value="0">
                                        </div>
                                        <div class="form-group">
                                            <label for="name_editing">Name</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i
                                                            class="bi bi-card-text"></i></span>
                                                </div>
                                                <input id="name_editing" name="name" placeholder="Name product"
                                                    value="<?php echo empty($name_editing) ? "" : $name_editing; ?>" type="text"
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div style="border-radius: 10px; border: 1px solid black; padding: 5px; background-color: black; color: white;"
                                                id="markdown_preview"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description <b>(Markdown supported)</b> <a href="https://www.markdownguide.org/basic-syntax/">guide of markdown</a></label>
                                            <textarea id="description" name="description" placeholder="Description"
                                                class="form-control" style="height: 300px;"><?php echo empty($description_editing) ? "" : $description_editing; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="head_editing">Head description</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i
                                                            class="bi bi-stickies-fill"></i></span>
                                                </div>
                                                <input id="head_editing" name="head" placeholder="Head description"
                                                    value="<?php echo empty($head_editing) ? "" : $head_editing; ?>" type="text"
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="amount_editing">Amount <b>(Remember stripe regex)</b></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i
                                                            class="bi bi-currency-euro"></i></span>
                                                </div>
                                                <input id="amount_editing" name="amount"
                                                    placeholder="Amount (0300 = 3.00€)"
                                                    value="<?php echo empty($amount_editing) ? "" : $amount_editing; ?>" type="text"
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="image_editing">Image</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1"><i
                                                            class="bi bi-card-image"></i></span>
                                                </div>
                                                <input id="image_editing" name="image" placeholder="Image url"
                                                    value="<?php echo empty($image_editing) ? "" : $image_editing; ?>" type="text"
                                                    class="form-control">
                                            </div>
                                            <input type="file" id="file" name="filename" accept="image/*">
                                        </div>
                                        <div class="form-group">
                                            <label for="show_more_btn_editing">Show more button</label>
                                            <div class="col-sm-12 border-bottom">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i
                                                                class="bi bi-info-circle-fill"></i></span>
                                                    </div>
                                                    <select name="show_more_btn" id="show_more_btn"
                                                        class="form-select shadow-none">
                                                        <?php if ($show_more_btn_editing == 1) { ?>
                                                        <option value="1">visible</option>
                                                        <option value="0">hidden</option>
                                                        <?php } else { ?>
                                                        <option value="0">hidden</option>
                                                        <option value="1">visible</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="deleted_editing">Deleted</label>
                                            <div class="col-sm-12 border-bottom">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i
                                                                class="bi bi-ban"></i></span>
                                                    </div>
                                                    <select name="deleted" id="deleted" class="form-select shadow-none">
                                                        <?php if ($deleted_editing == 0) { ?>
                                                        <option value="0">visible</option>
                                                        <option value="1">deleted</option>
                                                        <?php } else { ?>
                                                        <option value="1">deleted</option>
                                                        <option value="0">visible</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div style="float: left;">
                                            <?php 
                                        //NOTE - Button Update and Add check
                                        if(filter_var($_GET["id"], FILTER_SANITIZE_STRING) != "add") { 
                                        ?>
                                            <button type="submit" name="update" class="btn btn-info text-white"><i
                                                    class="bi bi-arrow-clockwise"></i> Update</button>
                                            <a onclick="confirm()" class="btn btn-danger text-white"><i
                                                class="bi bi-person-dash-fill"></i> Delete permanently</a>

                                                <button type="submit" name="delete" id="delete" hidden> Delete </button>
                                            <?php } else { ?>
                                            <button type="submit" name="add" class="btn btn-success text-white"><i
                                                    class="bi bi-cart-plus"></i> Add</button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        </form>
                        <script>
                        function togglePreview(e){
                            console.log(e);
                        }

                        window.onload = function() { //NOTE - Fixed a bug not doing the preview when show the page
                            var easyMDE = new EasyMDE({ toolbar: [
                                'bold', 'italic', 'strikethrough',
                                'heading', 'quote', 'code',
                                'unordered-list', 'ordered-list',
                                'link', 'image', 'horizontal-rule',
                                'undo', 'redo',
                            ], spellChecker: false, sideBySideFullscreen: false, element: document.getElementById('description') });

                            easyMDE.codemirror.on("change", () => {
                                document.getElementById('markdown_preview').innerHTML = easyMDE.value();
                            });

                            document.getElementById('markdown_preview').innerHTML = $('#description').val();

                            <?php 
                            if (filter_var($_GET["id"], FILTER_SANITIZE_STRING) != "add") 
                            { 
                                $product_id = filter_var($_GET["id"], FILTER_SANITIZE_STRING);

                                $servers_selected = [];
                                $servers_selected_non_array = "";
                                

                                $servers_selected_non_array = $servers_editing;
                                $servers_selected = explode(',', $servers_editing);
                                ?>
                                // Hacemos un push de todos los servidores asociados
                                var servers_selected = [<?php echo $servers_selected_non_array; ?>];
                                var servers_array = [<?php echo $servers_selected_non_array; ?>];
                                $("#servers_selected").val(servers_selected);
                                // selected: true
                                new MultiSelect('#servers', {
                                    name: "servers",
                                    search: false,
                                    placeholder: 'Select servers',
                                    data: [
                                        <?php
                                        foreach ($queries->select("servers", "ORDER BY `id` ASC") as $row) {
                                            $selected = in_array($row["id"], $servers_selected) ? "true" : "false";
                                        ?> 
                                        {
                                            value: '<?php echo $row["id"]; ?>',
                                            text: '<?php echo $row["ip"]; ?>:<?php echo $row["port"]; ?>',
                                            selected: <?php echo $selected; ?>,
                                        },
                                        <?php
                                        }
                                        ?>
                                    ],
                                    onSelect: function(value, text, element) {
                                        servers_array.push(value);
                                        console.log(servers_array);
                                        $("#servers_selected").val(servers_array);
                                    },
                                    onUnselect: function(value, text, element) {
                                        servers_array.pop(value);
                                        console.log(servers_array);
                                        $("#servers_selected").val(servers_array);
                                    }
                                });
                            <?php 
                            }
                            else
                            {
                            ?>
                            var servers_array = [];

                            new MultiSelect('#servers', {
                                name: "servers",
                                search: false,
                                placeholder: 'Select servers',
                                data: [
                                    <?php
                                    foreach ($queries->select("servers", "ORDER BY `id` ASC") as $row) {
                                    ?> 
                                    {
                                        value: '<?php echo $row["id"]; ?>',
                                        text: '<?php echo $row["ip"]; ?>:<?php echo $row["port"]; ?>',
                                    },
                                    <?php
                                    }
                                    ?>
                                ],
                                onSelect: function(value, text, element) {
                                    servers_array.push(value);
                                    console.log(servers_array);
                                    $("#servers_selected").val(servers_array);
                                },
                                onUnselect: function(value, text, element) {
                                    servers_array = servers_array.filter(function(e) { return e !== value });
                                    console.log(servers_array);
                                    $("#servers_selected").val(servers_array);
                                }
                            });
                            <?php } ?>

                        };
                        </script>
                        <?php 
                    } 
                    ?>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $('a#image_preview').miniPreview({ 
                prefetch: 'none',
                height: "125",
                width: "125"
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