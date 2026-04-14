<?php

use hail812\adminlte\widgets\Menu;
use yii\helpers\Html;

$userLogado = Yii::$app->user->identity;

?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link py-0">
        <?= Html::img('@web/img/BA5_Brasao', [
            'alt' => 'Brasao BA5',
            'class' => 'img-logo-dashboard'
        ]) ?>
        <span class="brand-text font-weight-light"><?=Html::encode('Administração')?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?=$assetDir?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <?= Html::a(
                    $userLogado->username, //Texto que aparece no botão (nome do dono)
                    ['user/view', 'id' => $userLogado->id], //A rota para onde vai (backend/user/view)
                    [
                        'target' => '_blank',
                    ]
                );
                ?>
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
                    // DASHBOARD
                    ['label' => 'Página Inicial', 'url' => ['site/index'], 'icon' => 'home'],
                    ['label' => 'Gestão de Utilizadores', 'url' => ['user/index'], 'icon' => 'user'],
                    ['label' => 'Gestão de Pedidos', 'url' => ['request/index'], 'icon' => 'inbox'],
                    ['label' => 'Gestão de Alojamentos', 'url' => ['lodging-site/index'], 'icon' => 'hotel'],
                    ['label' => 'Gestão Decision Log', 'url' => ['decision-log/index'], 'icon' => 'clipboard-list'],
                    ['label' => 'Gestão de Incidentes', 'url' => ['incident/index'], 'icon' => 'exclamation-triangle'],

                    // LOCALIZAÇÃO / TERRENO
                    [
                        'label' => 'Localização',
                        'icon' => 'map-marked-alt',
                        'options' => ['class' => 'nav-item nav-loc'],
                        'items' => [
                            ['label' => 'Locations', 'url' => ['location/index'], 'icon' => 'map-marker-alt'],
                            ['label' => 'Location Types', 'url' => ['location-type/index'], 'icon' => 'tags'],
                            ['label' => 'Branches', 'url' => ['branch/index'], 'icon' => 'sitemap'],
                        ],
                    ],

                    // LOGÍSTICA
                    [
                        'label' => 'Logística',
                        'icon' => 'warehouse',
                        'options' => ['class' => 'nav-item nav-log'],
                        'items' => [

                        ],
                    ],

                    // ADMINISTRAÇÃO
                    [
                        'label' => 'Administração',
                        'icon' => 'users-cog',
                        'options' => ['class' => 'nav-item nav-admin'],
                        'items' => [
                            ['label' => 'Audit Log', 'url' => ['audit-log/index'], 'icon' => 'history'],
                            ['label' => 'Entity Updates', 'url' => ['entity-update/index'], 'icon' => 'exchange-alt'],
                            ['label' => 'Entities', 'url' => ['entity/index'], 'icon' => 'database'],
                        ],
                    ],

                    // CONFIGURAÇÃO
                    [
                        'label' => 'Configuração',
                        'icon' => 'cogs',
                        'options' => ['class' => 'nav-item nav-config'],
                        'items' => [
                            ['label' => 'Priorities', 'url' => ['priority/index'], 'icon' => 'bolt'],
                            ['label' => 'Status Types', 'url' => ['status-type/index'], 'icon' => 'toggle-on'],
                            ['label' => 'Incident Types', 'url' => ['incident-type/index'], 'icon' => 'list'],
                            ['label' => 'Entity Types', 'url' => ['entity-type/index'], 'icon' => 'layer-group'],
                            ['label' => 'Request Types', 'url' => ['request-type/index'], 'icon' => 'layer-group'],
                        ],
                    ],
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>