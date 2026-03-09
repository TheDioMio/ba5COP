<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\LodgingSite $model */
/** @var yii\data\ActiveDataProvider $entriesDataProvider */

$this->title = '';
$breadcrumbsTitle = 'Alojamento: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Alojamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $breadcrumbsTitle;

$occupied = max(0, (int)$model->capacity_total - (int)$model->capacity_available);
$locationLabel = $model->location ? $model->location->name : ('#' . $model->location_id);
?>

<div class="lodging-site-view fade-in-up">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 font-weight-bold text-primary">
                <i class="fas fa-bed me-2"></i><?= Html::encode($model->name) ?>
            </h1>

            <p class="text-muted mb-0">
                <?= 'Alojamento #' . $model->id ?> |
                <span class="badge bg-secondary">
                    <?= Html::encode($locationLabel) ?>
                </span>
            </p>
        </div>

        <div class="d-flex gap-2">
            <?= Html::a('<i class="fas fa-arrow-left"></i>', ['index'], [
                'class' => 'btn btn-outline-secondary',
                'title' => 'Voltar',
            ]) ?>

            <?= Html::a('<i class="fas fa-edit"></i>', ['update', 'id' => $model->id], [
                'class' => 'btn btn-primary',
                'title' => 'Editar alojamento',
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow border-0 overflow-hidden">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-building me-2"></i>Dados do Alojamento
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
                                'label' => 'Nome',
                                'attribute' => 'name',
                                'contentOptions' => ['class' => 'align-middle'],
                            ],
                            [
                                'label' => 'Localização',
                                'value' => $locationLabel,
                                'contentOptions' => ['class' => 'align-middle'],
                            ],
                            [
                                'label' => 'Capacidade Total',
                                'attribute' => 'capacity_total',
                                'contentOptions' => ['class' => 'align-middle'],
                            ],
                            [
                                'label' => 'Capacidade Disponível',
                                'attribute' => 'capacity_available',
                                'contentOptions' => ['class' => 'align-middle'],
                            ],
                            [
                                'label' => 'Notas',
                                'attribute' => 'notes',
                                'format' => 'ntext',
                                'value' => $model->notes ?: 'Não definido',
                                'contentOptions' => ['class' => 'align-middle'],
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 text-center h-100">
                <div class="card-body p-5">
                    <div class="mb-3 d-flex justify-content-center">
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-light"></i>
                            <i class="fas fa-bed fa-stack-1x text-secondary"></i>
                        </span>
                    </div>

                    <h4 class="font-weight-bold mb-1"><?= Html::encode($model->name) ?></h4>
                    <span class="badge bg-info text-dark"><?= Html::encode($locationLabel) ?></span>

                    <hr>

                    <div class="row text-center mb-2">
                        <div class="col-4">
                            <span class="text-muted">Total</span>
                        </div>
                        <div class="col-4">
                            <span class="text-muted">Livre</span>
                        </div>
                        <div class="col-4">
                            <span class="text-muted">Ocupado</span>
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="col-4">
                            <span class="fw-semibold"><?= Html::encode($model->capacity_total) ?></span>
                        </div>
                        <div class="col-4">
                            <span class="fw-semibold"><?= Html::encode($model->capacity_available) ?></span>
                        </div>
                        <div class="col-4">
                            <span class="fw-semibold"><?= Html::encode($occupied) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow border-0 overflow-hidden">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Entradas Associadas
            </h5>

            <div class="ms-auto">
                <?= Html::a('<i class="fas fa-plus-circle"></i>', ['/lodging-entry/create', 'lodging_site_id' => $model->id], [
                    'class' => 'btn btn-success btn-sm',
                    'title' => 'Adicionar entrada',
                ]) ?>
            </div>
        </div>

        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $entriesDataProvider,
                'tableOptions' => ['class' => 'table table-hover table-striped table-sm mb-0'],
                'layout' => "{items}\n<div class='p-3'>{summary}\n{pager}</div>",
                'columns' => [
                    [
                        'attribute' => 'branch_id',
                        'label' => 'Ramo',
                        'value' => function ($entry) {
                            return $entry->branch ? $entry->branch->description : ('#' . $entry->branch_id);
                        },
                    ],
                    [
                        'attribute' => 'people_count',
                        'label' => 'N.º Pessoas',
                    ],
                    [
                        'attribute' => 'checkin_at',
                        'label' => 'Check-in',
                        'format' => ['datetime', 'php:d/m/Y H:i'],
                    ],
                    [
                        'attribute' => 'checkout_at',
                        'label' => 'Check-out',
                        'format' => ['datetime', 'php:d/m/Y H:i'],
                        'value' => function ($entry) {
                            return $entry->checkout_at ?: null;
                        },
                    ],
                    [
                        'attribute' => 'notes',
                        'label' => 'Notas',
                        'value' => function ($entry) {
                            return $entry->notes ?: '—';
                        },
                    ],
                    [
                        'class' => yii\grid\ActionColumn::class,
                        'header' => 'Ações',
                        'template' => '{view} {update} {delete}',
                        'urlCreator' => function ($action, $entry) {
                            return ['/lodging-entry/' . $action, 'id' => $entry->id];
                        },
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>