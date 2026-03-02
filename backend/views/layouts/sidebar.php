<?php

use hail812\adminlte\widgets\Menu;

?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="<?=$assetDir?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?=$assetDir?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <!-- href be escaped -->
        <!-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?php
            echo Menu::widget([
                'items' => [
                    ['label' => 'Página Inicial', 'url' => ['site/index'], 'iconStyle' => 'far'],
                    ['label' => 'Branch', 'url' => ['branch/index'], 'iconStyle' => 'far'],
                    ['label' => 'Incident Type', 'url' => ['incident-type/index'], 'iconStyle' => 'far'],
                    ['label' => 'Location Type', 'url' => ['location-type/index'], 'iconStyle' => 'far'],
                    ['label' => 'Lodging Entry', 'url' => ['lodging-entry/index'], 'iconStyle' => 'far'],
                    ['label' => 'Lodging Site', 'url' => ['lodging-site/index'], 'iconStyle' => 'far'],
                    ['label' => 'Location', 'url' => ['location/index'], 'iconStyle' => 'far'],
                    ['label' => 'Task', 'url' => ['task/index'], 'iconStyle' => 'far'],
                    ['label' => 'Incident', 'url' => ['incident/index'], 'iconStyle' => 'far'],
                    ['label' => 'Decision Log', 'url' => ['decision-log/index'], 'iconStyle' => 'far'],
                    ['label' => 'User', 'url' => ['user/index'], 'iconStyle' => 'far'],
                    ['label' => 'Priority', 'url' => ['priority/index'], 'iconStyle' => 'far'],
                    ['label' => 'Request', 'url' => ['request/index'], 'iconStyle' => 'far'],
                    ['label' => 'Entity', 'url' => ['entity/index'], 'iconStyle' => 'far'],
                    ['label' => 'Entity Type', 'url' => ['entity-type/index'], 'iconStyle' => 'far'],
                    ['label' => 'Status Type', 'url' => ['status-type/index'], 'iconStyle' => 'far'],
                    ['label' => 'Entity Update', 'url' => ['entity-update/index'], 'iconStyle' => 'far'],
                    ['label' => 'Audit Log', 'url' => ['audit-log/index'], 'iconStyle' => 'far'],
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>