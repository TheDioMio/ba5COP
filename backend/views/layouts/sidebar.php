<?php

use hail812\adminlte\widgets\Menu;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\web\View;

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
                    ['label' => 'Página Inicial', 'url' => ['site/index'], 'icon' => 'home'],
                    [
                        'label' => 'Módulos de Gestão',
                        'icon' => 'tools',
                        'options' => ['class' => 'nav-item nav-loc'],
                        'items' => [
                            ['label' => 'Gestão de Utilizadores', 'url' => ['user/index'], 'icon' => 'user'],
                            ['label' => 'Gestão de Pedidos', 'url' => ['request/index'], 'icon' => 'inbox'],
                            ['label' => 'Gestão de Alojamentos', 'url' => ['lodging-site/index'], 'icon' => 'hotel'],
                            ['label' => 'Gestão Decision Log', 'url' => ['decision-log/index'], 'icon' => 'clipboard-list'],
                            ['label' => 'Gestão de Incidentes', 'url' => ['incident/index'], 'icon' => 'exclamation-triangle'],
                        ],
                    ],

                    // LOCALIZAÇÃO / TERRENO
                    [
                        'label' => 'Localização',
                        'icon' => 'map-marked-alt',
                        'options' => ['class' => 'nav-item nav-loc'],
                        'items' => [
                            ['label' => 'Locations', 'url' => ['location/index'], 'icon' => 'map-marker-alt'],
                            ['label' => 'Ramos', 'url' => ['branch/index'], 'icon' => 'sitemap'],
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
                        ],
                    ],

                    // ZONA PERIGOSA
                    [
                        'label' => 'DANGER ZONE',
                        'icon' => 'cogs',
                        'options' => ['class' => 'nav-item nav-danger-zone'],
                        'items' => [
                            ['label' => 'Entidades', 'url' => ['entity/index'], 'icon' => 'database'],
                            [
                                'label' => 'Tipos de Dados', 'icon' => 'layer-group',
                                'items' => [

                                    ['label' => 'Tipos de Prioridades', 'url' => ['priority/index'], 'icon' => 'none'],
                                    ['label' => 'Tipos de Status', 'url' => ['status-type/index'], 'icon' => 'none'],
                                    ['label' => 'Tipos de Incidentes', 'url' => ['incident-type/index'], 'icon' => 'none'],
                                    ['label' => 'Tipos de Entidades', 'url' => ['entity-type/index'], 'icon' => 'none'],
                                    ['label' => 'Tipos de Pedidos', 'url' => ['request-type/index'], 'icon' => 'none'],
                                    ['label' => 'Tipos de Localizações', 'url' => ['location-type/index'], 'icon' => 'none'],
                                ]
                            ],
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

<?php Modal::begin([
    'id' => 'dangerZoneModal',
    'title' => '
        <div class="danger-zone-modal-title-wrap">
            <div>
                <div class="danger-zone-modal-title-text">Danger Zone</div>
            </div>
        </div>
    ',
    'size' => Modal::SIZE_LARGE,
    'centerVertical' => true,
    'options' => ['class' => 'fade danger-zone-modal'],
    'headerOptions' => ['class' => 'danger-zone-modal-header'],
    'bodyOptions' => ['class' => 'danger-zone-modal-body'],
    'closeButton' => [
        'class' => 'btn-close btn-close-white',
        'aria-label' => 'Fechar',
    ],
]); ?>

<div class="danger-zone-alert-box">
    <div class="danger-zone-alert-icon">
        <i class="fas fa-shield-alt"></i>
    </div>
    <div class="danger-zone-alert-content">
        <div class="danger-zone-alert-title">Acesso a tabelas sensíveis</div>
        <div class="danger-zone-alert-text">
            Está prestes a entrar numa zona administrativa sensível. Alterações incorretas podem comprometer a lógica da
            aplicação, afetar relações internas da base de dados e provocar comportamentos inesperados no sistema.
        </div>
    </div>
</div>

<div class="danger-zone-section">
    <div class="danger-zone-section-title">Impactos possíveis</div>

    <div class="danger-zone-impact-list">
        <div class="danger-zone-impact-item">
            <span class="danger-zone-impact-badge is-danger">
                <i class="fas fa-bug"></i>
            </span>
            <div class="danger-zone-impact-content">
                <strong>Falhas no sistema</strong>
                <div class="danger-zone-impact-desc">
                    Funcionalidades podem deixar de responder corretamente.
                </div>
            </div>
        </div>

        <div class="danger-zone-impact-item">
            <span class="danger-zone-impact-badge is-warning">
                <i class="fas fa-database"></i>
            </span>
            <div class="danger-zone-impact-content">
                <strong>Inconsistência de dados</strong>
                <div class="danger-zone-impact-desc">
                    Estados, tipos e prioridades podem deixar de estar alinhados com a lógica da aplicação.
                </div>
            </div>
        </div>

        <div class="danger-zone-impact-item">
            <span class="danger-zone-impact-badge is-dark">
                <i class="fas fa-unlink"></i>
            </span>
            <div class="danger-zone-impact-content">
                <strong>Quebra de relações críticas</strong>
                <div class="danger-zone-impact-desc">
                    Mudanças indevidas podem afetar relações na base de dados, validações e comportamento de módulos dependentes.
                </div>
            </div>
        </div>
    </div>
</div>

<div class="danger-zone-confirm-box">
    <i class="fas fa-circle-exclamation"></i>
    <span>Confirma que pretende continuar?</span>
</div>

<div class="danger-zone-actions">
    <button type="button" id="dangerCancel" class="danger-zone-btn danger-zone-btn-cancel">
        <i class="fas fa-arrow-left"></i>
        <span>Cancelar</span>
    </button>

    <button type="button" id="dangerConfirm" class="danger-zone-btn danger-zone-btn-confirm">
        <i class="fas fa-unlock-alt"></i>
        <span>Continuar</span>
    </button>
</div>

<?php Modal::end(); ?>

<?php
$this->registerJs(<<<JS
let allowOpenDangerZone = false;

function initDangerZoneWarning() {
    const dangerItem = document.querySelector('.nav-danger-zone');
    const dangerLink = dangerItem ? dangerItem.querySelector('.nav-link') : null;
    const modalEl = document.getElementById('dangerZoneModal');
    const confirmBtn = document.getElementById('dangerConfirm');
    const cancelBtn = document.getElementById('dangerCancel');

    if (!dangerItem || !dangerLink || !modalEl || !confirmBtn || !cancelBtn) {
        console.log('Danger Zone: elementos não encontrados.');
        return;
    }

    const modal = new bootstrap.Modal(modalEl);

    dangerLink.addEventListener('click', function (e) {
        if (!allowOpenDangerZone) {
            e.preventDefault();
            e.stopPropagation();
            modal.show();
        }
    });

    confirmBtn.addEventListener('click', function () {
        allowOpenDangerZone = true;
        modal.hide();

        dangerItem.classList.add('menu-open');

        const submenu = dangerItem.querySelector('.nav-treeview');
        if (submenu) {
            submenu.style.display = 'block';
        }
    });

    cancelBtn.addEventListener('click', function () {
        modal.hide();
    });
}

initDangerZoneWarning();
JS, View::POS_READY);
?>
