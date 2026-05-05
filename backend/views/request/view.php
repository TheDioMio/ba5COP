<?php

use common\models\Entity;
use common\models\StatusType;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Request $model */

$this->title = 'Pedido #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Gestão de Pedidos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

YiiAsset::register($this);

$statusDescription = $model->statusType?->description ?? 'Desconhecido';

$statusClass = match ($statusDescription) {
    'NOVO' => 'badge-primary',
    'EM ANÁLISE' => 'badge-warning',
    'APROVADO' => 'badge-success',
    'REJEITADO' => 'badge-danger',
    default => 'badge-secondary',
};

$novoId = StatusType::find()
    ->select('id')
    ->where([
        'entity_type_id' => Entity::REQUEST_ID,
        'description' => 'NOVO',
    ])
    ->scalar();

$emAnaliseId = StatusType::find()
    ->select('id')
    ->where([
        'entity_type_id' => Entity::REQUEST_ID,
        'description' => 'EM ANÁLISE',
    ])
    ->scalar();

$isPending = in_array((int)$model->status_type_id, [
    (int)$novoId,
    (int)$emAnaliseId,
], true);

?>

<div class="request-view-user-pro">

    <div class="mb-3 text-right">
        <?= Html::a('<i class="fas fa-arrow-left"></i>',
            ['index'],
            [
                'class' => 'btn btn-outline-secondary mr-1',
                'title' => 'Voltar',
            ]
        ) ?>

        <?= Html::a('<i class="fas fa-edit"></i>',
            ['update', 'id' => $model->id],
            [
                'class' => 'btn btn-primary mr-1',
                'title' => 'Editar',
            ]
        ) ?>

        <?= Html::a('<i class="fas fa-trash"></i>',
            ['delete', 'id' => $model->id],
            [
                'class' => 'btn btn-danger mr-1',
                'title' => 'Apagar',
                'data' => [
                    'confirm' => 'Tem a certeza que deseja apagar este pedido?',
                    'method' => 'post',
                ],
            ]
        ) ?>
    </div>

    <div class="row">

        <div class="col-md-4">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clipboard-list mr-1"></i>
                        <?= Html::encode('Resumo do Pedido') ?>
                    </h3>
                </div>

                <div class="card-body box-profile">

                    <div class="text-center mb-3">
                        <span class="img-circle elevation-2 d-inline-flex align-items-center justify-content-center bg-light"
                              style="width: 80px; height: 80px; font-size: 2rem;">
                            <span class="fa-stack">
                                <i class="fas fa-circle fa-stack-2x text-light"></i>
                                <i class="fas fa-file-alt fa-stack-1x text-secondary"></i>
                            </span>
                        </span>
                    </div>

                    <h3 class="profile-username text-center">
                        Pedido #<?= Html::encode($model->id) ?>
                    </h3>

                    <p class="text-muted text-center">
                        <?= $model->is_external ? 'Pedido Externo' : 'Pedido Interno' ?>
                    </p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b><?= Html::encode('Origem') ?></b>
                            <a class="float-right">
                                <?= Html::encode($model->origin ?: 'Sem origem') ?>
                            </a>
                        </li>

                        <li class="list-group-item">
                            <b><?= Html::encode('Tipo') ?></b>
                            <a class="float-right">
                                <?= Html::encode($model->requestType?->description ?? 'Sem tipo') ?>
                            </a>
                        </li>

                        <li class="list-group-item">
                            <b><?= Html::encode('Prioridade') ?></b>
                            <a class="float-right">
                                <?= Html::encode($model->priority?->description ?? 'Sem prioridade') ?>
                            </a>
                        </li>

                        <li class="list-group-item">
                            <b><?= Html::encode('Estado') ?></b>
                            <span class="float-right">
                                <?= Html::tag('span', Html::encode($statusDescription), [
                                    'class' => "badge {$statusClass}",
                                ]) ?>
                            </span>
                        </li>

                        <li class="list-group-item">
                            <b><?= Html::encode('Criado em') ?></b>
                            <a class="float-right">
                                <?= Yii::$app->formatter->asDatetime($model->created_at, 'short') ?>
                            </a>
                        </li>
                    </ul>

                    <?php if ($isPending): ?>
                        <div class="row mt-4">
                            <div class="col-6">
                                <?= Html::a('Negar',
                                    ['deny-request', 'id' => $model->id],
                                    [
                                        'class' => 'btn btn-danger btn-block',
                                        'data' => [
                                            'confirm' => 'Tem a certeza que deseja rejeitar este pedido?',
                                            'method' => 'post',
                                        ],
                                    ]
                                ) ?>
                            </div>

                            <div class="col-6">
                                <?= Html::a('Aceitar',
                                    ['accept-request', 'id' => $model->id],
                                    [
                                        'class' => 'btn btn-success btn-block',
                                        'data' => [
                                            'confirm' => 'Tem a certeza que deseja aprovar este pedido?',
                                            'method' => 'post',
                                        ],
                                    ]
                                ) ?>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card card-info card-outline shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list-alt mr-1"></i>
                        <?= Html::encode('Detalhes do Pedido') ?>
                    </h3>
                </div>

                <div class="card-body p-0">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-hover mb-0'],
                        'attributes' => [
                            [
                                'attribute' => 'id',
                                'label' => 'ID',
                            ],
                            [
                                'attribute' => 'is_external',
                                'label' => 'Tipo de Pedido',
                                'value' => function ($model) {
                                    return $model->is_external ? 'Externo' : 'Interno';
                                },
                            ],
                            [
                                'attribute' => 'origin',
                                'label' => 'Origem',
                            ],
                            [
                                'attribute' => 'details',
                                'label' => 'Detalhes',
                                'format' => 'ntext',
                            ],
                            [
                                'label' => 'Tipo de Pedido',
                                'value' => function ($model) {
                                    return $model->requestType?->description ?? 'Sem tipo';
                                },
                            ],
                            [
                                'label' => 'Prioridade',
                                'value' => function ($model) {
                                    return $model->priority?->description ?? 'Sem prioridade';
                                },
                            ],
                            [
                                'label' => 'Estado',
                                'format' => 'raw',
                                'value' => function ($model) use ($statusClass, $statusDescription) {
                                    return Html::tag('span', Html::encode($statusDescription), [
                                        'class' => "badge {$statusClass}",
                                    ]);
                                },
                            ],
                            [
                                'attribute' => 'created_at',
                                'label' => 'Criado em',
                                'value' => function ($model) {
                                    return Yii::$app->formatter->asDatetime($model->created_at, 'medium');
                                },
                            ],
                            [
                                'attribute' => 'entity_id',
                                'label' => 'Entidade associada',
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>

    </div>
</div>