<?php

use hail812\adminlte\widgets\Menu;
use yii\bootstrap5\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
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
        <?php if (Yii::$app->user->can('admin')): ?>
            <span class="brand-text font-weight-light"><?= Html::encode('Administração') ?></span>
        <?php else: ?>
            <span class="brand-text font-weight-light"><?= Html::encode('Gestão do COP') ?></span>
        <?php endif; ?>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <?= Html::a(
                    'Bem-vindo(a), ' . $userLogado->username,
                    ['user/view', 'id' => $userLogado->id],
                    [
                        'target' => '_blank',
                    ]
                ); ?>
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
                            [
                                'label' => 'Gestão de Utilizadores',
                                'url' => ['user/index'],
                                'icon' => 'user',
                                'visible' => Yii::$app->user->can('user.manage'),
                            ],
                            [
                                'label' => 'Gestão de Pedidos',
                                'url' => ['request/index'],
                                'icon' => 'inbox',
                                'visible' => Yii::$app->user->can('request.manage'),
                            ],
                            [
                                'label' => 'Gestão de Alojamentos',
                                'url' => ['lodging-site/index'],
                                'icon' => 'hotel',
                                'visible' => Yii::$app->user->can('lodging.manage'),
                            ],
                            [
                                'label' => 'Gestão Decision Log',
                                'url' => ['decision-log/index'],
                                'icon' => 'clipboard-list',
                                'visible' => Yii::$app->user->can('decisionLog.manage'),
                            ],
                            [
                                'label' => 'Gestão de Incidentes',
                                'url' => ['incident/index'],
                                'icon' => 'exclamation-triangle',
                                'visible' => Yii::$app->user->can('incident.manage'),
                            ],
                        ],
                    ],

                    // LOCALIZAÇÃO / TERRENO
                    [
                        'label' => 'Localização',
                        'icon' => 'map-marked-alt',
                        'options' => ['class' => 'nav-item nav-loc'],
                        'items' => [
//                            ['label' => 'Locations', 'url' => ['location/index'], 'icon' => 'map-marker-alt'],
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
                            [
                                'label' => 'Audit Log',
                                'url' => ['audit-log/index'],
                                'icon' => 'history',
                                'visible' => Yii::$app->user->can('audit.view'),
                            ],
//                            ['label' => 'Entity Updates', 'url' => ['entity-update/index'], 'icon' => 'exchange-alt'],
                        ],
                    ],

                    // ZONA PERIGOSA
                    [
                        'label' => 'DANGER ZONE',
                        'icon' => 'cogs',
                        'options' => ['class' => 'nav-item nav-danger-zone'],
                        'visible' => Yii::$app->user->can('sensibleEntity.manage'),
                        'items' => [
                            ['label' => 'Entidades', 'url' => ['entity/index'], 'icon' => 'database'],
                            ['label' => 'Ramos', 'url' => ['branch/index'], 'icon' => 'sitemap'],
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
                            [
                                'label' => '<div class="danger-actions-title">Ações perigosas</div>',
                                'encode' => false,
                                'header' => true,
                                'options' => ['class' => 'danger-actions-header'],
                            ],
                            [
                                'label' => '<span class="danger-action-label">Limpar mapa</span>',
                                'url' => '#',
                                'icon' => 'none',
                                'encode' => false,
                                'options' => ['class' => 'danger-action-item danger-action-map'],
                            ],
                            [
                                'label' => '<span class="danger-action-label">Limpar base de dados</span>',
                                'url' => '#',
                                'icon' => 'none',
                                'encode' => false,
                                'options' => ['class' => 'danger-action-item danger-action-db'],
                            ],
                        ],
                    ],
                    [
                        'label' => 'Abrir COP',
                        'url' => Yii::$app->params['frontendUrl'],
                        'icon' => 'external-link-alt',
                        'options' => ['class' => 'nav-item nav-open-cop'],
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



<?php Modal::begin([
    'id' => 'dangerConfirmModalMap',
    'title' => '
        <div class="danger-zone-modal-title-wrap">
            <div>
                <div class="danger-zone-modal-title-text">Confirmação</div>
            </div>
        </div>
    ',
    'size' => Modal::SIZE_DEFAULT,
    'centerVertical' => true,
    'options' => ['class' => 'fade danger-zone-modal'],
    'headerOptions' => ['class' => 'danger-zone-modal-header'],
    'bodyOptions' => ['class' => 'danger-zone-modal-body'],
    'closeButton' => [
        'class' => 'btn-close btn-close-white',
    ],
]); ?>

<div class="danger-zone-alert-box">
    <div class="danger-zone-alert-icon">
        <i class="fas fa-triangle-exclamation"></i>
    </div>
    <div class="danger-zone-alert-content">
        <div class="danger-zone-alert-title">Ação irreversível</div>
        <div class="danger-zone-alert-text" id="dangerConfirmText">
            Tem a certeza que pretende continuar?
        </div>
    </div>
</div>

<div class="danger-zone-actions">
    <button type="button" id="dangerConfirmCancel" class="danger-zone-btn danger-zone-btn-cancel">
        Cancelar
    </button>

    <button type="button" id="dangerConfirmOk" class="danger-zone-btn danger-zone-btn-confirm">
        Confirmar
    </button>
</div>

<?php Modal::end(); ?>



<?php Modal::begin([
    'id' => 'dangerConfirmModalDb',
    'title' => '
        <div class="danger-zone-modal-title-wrap">
            <div>
                <div class="danger-zone-modal-title-text">Confirmação crítica</div>
            </div>
        </div>
    ',
    'size' => Modal::SIZE_DEFAULT,
    'centerVertical' => true,
    'options' => ['class' => 'fade danger-zone-modal'],
    'headerOptions' => ['class' => 'danger-zone-modal-header'],
    'bodyOptions' => ['class' => 'danger-zone-modal-body'],
    'closeButton' => [
        'class' => 'btn-close btn-close-white',
    ],
]); ?>

<div class="danger-zone-alert-box">
    <div class="danger-zone-alert-icon">
        <i class="fas fa-database"></i>
    </div>
    <div class="danger-zone-alert-content">
        <div class="danger-zone-alert-title">Limpeza da base de dados</div>
        <div class="danger-zone-alert-text" id="dangerConfirmTextDb">
            Esta ação irá apagar toda a informação operacional do sistema.
        </div>
    </div>
</div>

<div class="danger-zone-confirm-box">
    <i class="fas fa-circle-exclamation"></i>
    <span>Esta ação não pode ser revertida.</span>
</div>

<div class="danger-zone-actions">
    <button type="button" id="dangerConfirmCancelDb" class="danger-zone-btn danger-zone-btn-cancel">
        Cancelar
    </button>

    <button type="button" id="dangerConfirmOkDb" class="danger-zone-btn danger-zone-btn-confirm">
        Limpar base de dados
    </button>
</div>

<?php Modal::end(); ?>



<?php
$cleanMapUrl = Url::to(['/location/clean-map']);
$cleanDBUrl = Url::to(['/site/clean-database']);

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

function showDangerConfirmMap(message) {
    return new Promise((resolve) => {
        const modalEl = document.getElementById('dangerConfirmModalMap');
        const modal = new bootstrap.Modal(modalEl);

        const textEl = document.getElementById('dangerConfirmText');
        const btnOk = document.getElementById('dangerConfirmOk');
        const btnCancel = document.getElementById('dangerConfirmCancel');

        textEl.innerText = message;

        function cleanup(result) {
            btnOk.removeEventListener('click', onOk);
            btnCancel.removeEventListener('click', onCancel);
            modal.hide();
            resolve(result);
        }

        function onOk() {
            cleanup(true);
        }

        function onCancel() {
            cleanup(false);
        }

        btnOk.addEventListener('click', onOk);
        btnCancel.addEventListener('click', onCancel);

        modal.show();
    });
}

function showDangerConfirmDb(message) {
    return new Promise((resolve) => {
        const modalEl = document.getElementById('dangerConfirmModalDb');
        const modal = new bootstrap.Modal(modalEl);

        const textEl = document.getElementById('dangerConfirmTextDb');
        const btnOk = document.getElementById('dangerConfirmOkDb');
        const btnCancel = document.getElementById('dangerConfirmCancelDb');

        textEl.innerText = message;

        function cleanup(result) {
            btnOk.removeEventListener('click', onOk);
            btnCancel.removeEventListener('click', onCancel);
            modal.hide();
            resolve(result);
        }

        function onOk() {
            cleanup(true);
        }

        function onCancel() {
            cleanup(false);
        }

        btnOk.addEventListener('click', onOk);
        btnCancel.addEventListener('click', onCancel);

        modal.show();
    });
}

document.querySelectorAll('.danger-action-map > a').forEach(el => {
    el.addEventListener('click', async function (e) {
        e.preventDefault();

        const confirmed = await showDangerConfirmMap(
            'Vai apagar TODAS as layers do mapa. Esta ação não pode ser revertida.'
        );

        if (!confirmed) return;

        try {
            const res = await fetch('$cleanMapUrl', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': yii.getCsrfToken()
                },
                credentials: 'same-origin',
                body: '{}'
            });

            if (!res.ok) {
                throw new Error(await res.text());
            }

            if (window.copMapInstance?.reload) {
                window.copMapInstance.reload();
            }

            alert('Mapa limpo com sucesso.');

        } catch (err) {
            console.error(err);
            alert(err.message);
        }
    });
});

document.querySelectorAll('.danger-action-db > a').forEach(el => {
    el.addEventListener('click', async function (e) {
        e.preventDefault();

        const confirmed = await showDangerConfirmDb(
            'Vai apagar TODA a informação operacional do sistema, mantendo apenas tabelas auxiliares, utilizadores e permissões.'
        );

        if (!confirmed) return;

        try {
            const res = await fetch('$cleanDBUrl', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': yii.getCsrfToken()
                },
                credentials: 'same-origin',
                body: '{}'
            });

            if (!res.ok) {
                throw new Error(await res.text());
            }

            alert('Base de dados limpa com sucesso.');
            location.reload();

        } catch (err) {
            console.error(err);
            alert(err.message);
        }
    });
});
JS, View::POS_READY);
?>
