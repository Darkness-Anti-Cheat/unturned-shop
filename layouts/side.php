<aside class="left-sidebar" data-sidebarbg="skin6">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="sidebar-item pt-2">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../index" aria-expanded="false">
                        <i class="fa fa-home" aria-hidden="true"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../shop" aria-expanded="false">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                        <span class="hide-menu">Shop</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../servers" aria-expanded="false">
                        <i class="fas fa-users" aria-hidden="true"></i>
                        <span class="hide-menu">Servers</span>
                    </a>
                </li>
                <br>
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="../terms" aria-expanded="false">
                        <i class="fas fa-hands-helping" aria-hidden="true"></i>
                        <span class="hide-menu">Terms and conditions</span>
                    </a>
                </li>
                <?php
                if(isset($_SESSION['steamid'])) {

                foreach($queries->select("users", " WHERE steamid=" . $steamid) as $row) 
                {		
                    $_SESSION["rank"] = $row["rank"];
                }

                if ($_SESSION["rank"] == "admin") { 
                ?>
                <br>
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <span class="d-flex">
                        <iconify-icon icon="solar:slider-vertical-minimalistic-line-duotone" class="fs-6"></iconify-icon>
                        </span>
                        <i class="bi bi-sliders2"></i>
                        <span class="hide-menu"> Manage</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/admin/manage_users"
                                aria-expanded="false">
                                <i class="fa fa-users" aria-hidden="true"></i>
                                <span class="hide-menu">Manage users</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/admin/manage_payments"
                                aria-expanded="false">
                                <i class="fa fa-credit-card" aria-hidden="true"></i>
                                <span class="hide-menu">Manage payments</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/admin/manage_products"
                                aria-expanded="false">
                                <i class="bi bi-basket2"></i>
                                <span class="hide-menu">Manage products</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/admin/manage_servers"
                                aria-expanded="false">
                                <i class="bi bi-hdd-rack-fill"></i>
                                <span class="hide-menu">Manage servers</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/admin/manage_settings"
                                aria-expanded="false">
                                <i class="bi bi-gear-fill"></i>
                                <span class="hide-menu">Manage settings</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/admin/manage_graphics"
                                aria-expanded="false">
                                <i class="bi bi-graph-up"></i>
                                <span class="hide-menu">Manage graphics</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <span class="d-flex">
                        <iconify-icon icon="solar:slider-vertical-minimalistic-line-duotone" class="fs-6"></iconify-icon>
                        </span>
                        <i class="bi bi-sliders"></i>
                        <span class="hide-menu"> Discord</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level">
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/admin/announcements"
                                aria-expanded="false">
                                <i class="fas fa-bullhorn" aria-hidden="true"></i>
                                <span class="hide-menu">Announcements discord</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!--<li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark has-arrow" href="javascript:void(0)" aria-expanded="false">
                        <span class="d-flex">
                        <iconify-icon icon="solar:slider-vertical-minimalistic-line-duotone" class="fs-6"></iconify-icon>
                        </span>
                        <i class="bi bi-body-text"></i>
                        <span class="hide-menu"> Integrations</span>
                    </a>
                </li>-->
                <?php 
                    } 
                }
                ?>
            </ul>

        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>