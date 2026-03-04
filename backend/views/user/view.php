<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = '';
$breadcrums = 'Perfil de ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Utilizadores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $breadcrums;

YiiAsset::register($this);
?>

<div class="user-view fade-in-up">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 font-weight-bold text-primary">
                <i class="fas fa-user-circle me-2"></i><?= Html::encode($model->username) ?>
            </h1>

            <p class="text-muted mb-0">
                <?= 'Utilizador #'.$model->id ?> |
                <span class="badge <?= $badge['class'] ?>">
                    <?= Html::encode($badge['label']) ?>
                </span>
            </p>
        </div>

        <div class="d-flex gap-2">
            <?= Html::a('<i class="fas fa-arrow-left"></i>',
                ['index'],
                [
                    'class' => 'btn btn-outline-secondary',
                    'title' => 'Voltar',
                ]
            ) ?>

            <?= Html::a('<i class="fas fa-edit"></i>',
                ['update', 'id' => $model->id],
                [
                    'class' => 'btn btn-primary',
                    'title' => 'Editar',
                ]
            ) ?>

            <?php
            // não deixar apagar o próprio user logado
            if ((int)$model->id === (int)Yii::$app->user->id) {
                echo Html::a('<i class="fas fa-trash"></i>', ['delete', '#'], [
                    'class' => 'btn btn-danger',
                    'title' => 'Apagar',
                    'onclick' => 'return false;',
                    'style' => 'opacity: 0.5; cursor: not-allowed;',
                ]);
            } else {
                echo Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'title' => 'Apagar',
                    'data' => [
                        'confirm' => 'Tem a certeza que deseja apagar este utilizador?',
                        'method' => 'post',
                    ],
                ]);
            }
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow border-0 overflow-hidden">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-id-card me-2"></i><?= Html::encode('Dados da Conta') ?>
                    </h5>
                </div>

                <div class="card-body p-0">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-hover table-striped mb-0 table-layout-fixed'],
                        'formatter' => [
                            'class' => 'yii\i18n\Formatter',
                            'nullDisplay' => '<span class="text-muted">Não definido</span>',
                        ],
                        'attributes' => [
                            [
                                'label' => 'Username',
                                'attribute' => 'username',
                                'contentOptions' => ['class' => 'align-middle'],
                            ],
                            [
                                'label' => 'Email',
                                'attribute' => 'email',
                                'format' => 'email',
                                'contentOptions' => ['class' => 'text-primary align-middle'],
                            ],
                            [
                                'label' => 'Cargo',
                                'value' => $roleDesc,
                                'contentOptions' => ['class' => 'align-middle'],
                            ],
                            [
                                'label' => 'Estado',
                                'value' => $badge['label'],
                                'contentOptions' => ['class' => 'align-middle'],
                            ],
                            [
                                'label' => 'Data de Registo',
                                'attribute' => 'created_at',
                                'format' => ['datetime', 'php:d/m/Y H:i'],
                                'contentOptions' => ['class' => 'align-middle'],
                            ],
                            [
                                'label' => 'Última Atualização',
                                'attribute' => 'updated_at',
                                'format' => ['datetime', 'php:d/m/Y H:i'],
                                'contentOptions' => ['class' => 'align-middle'],
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body p-5">
                    <div class="mb-3 d-flex justify-content-center">
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-light"></i>
                            <i class="fas fa-user fa-stack-1x text-secondary"></i>
                        </span>
                    </div>

                    <h4 class="font-weight-bold mb-1"><?= Html::encode($model->username) ?></h4>

                    <?php if ($roleDesc): ?>
                        <span class="ms-2 badge bg-info text-dark">
                            <?= Html::encode($roleDesc) ?>
                        </span>
                    <?php endif; ?>

                    <hr>

                    <div class="row text-center mb-2">
                        <div class="col-md-4">
                            <span class="text-muted">Tasks</span>
                        </div>

                        <div class="col-md-4">
                            <span class="text-muted">Incidents</span>
                        </div>

                        <div class="col-md-4">
                            <span class="text-muted">Requests</span>
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="col-md-4">
                            <span class="fw-semibold"><?= Html::encode('10') ?></span>
                        </div>

                        <div class="col-md-4">
                            <span class="fw-semibold"><?= Html::encode('40') ?></span>
                        </div>

                        <div class="col-md-4">
                            <span class="fw-semibold"><?= Html::encode('9') ?></span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>