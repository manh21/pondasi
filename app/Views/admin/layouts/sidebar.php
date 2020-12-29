<?php
$authFullname = isset($authFullname) ? $authFullname : 'No User';
?>
<aside class="main-sidebar sidebar-dark-lightblue elevation-4 sidebar-no-expand">
    <!-- Brand Logo -->
    <a href="<?php print_site_url(); ?>" class="brand-link">
        <span class="brand-image"><i class="fa fa-cogs" style="font-size: 24px;line-height: 32px;"></i></span>
        <span class="brand-text font-weight-light"><?php print_var($appName) ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php print_base_url("assets/admin/img/user2-160x160.jpg"); ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php print_var($authFullname) ?></a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar nav-flat nav-child-indent flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?php echo site_url(); ?>" target="_blank" class="nav-link">
                        <i class="nav-icon fas fa-blog"></i>
                        <p>
                            Show Website
                        </p>
                    </a>
                </li>
                <li class="nav-header">MAIN MENU</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            User Management
                            <i class="fas fa-angle-left right"></i>
                        </p>            
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= adminURL('admin/userlist')?>" class="nav-link">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    User List
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= adminURL('admin/usergroups')?>" class="nav-link">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    User Group
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="<?= adminURL('admin/settings') ?>" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Settings
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a target="_blank" href="https://api.whatsapp.com/send?phone=085735164799&text=Butuh%20bantuan%20untuk%20eVoting%20Pemilu%20Ketua%20Osisi%20dan%20MPK&source=&data=&app_absent=" class="nav-link">
                        <i class="nav-icon far fa-life-ring"></i>
                        <p>
                            Support
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= adminURL('admin/logout')?>" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            Log Out
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>