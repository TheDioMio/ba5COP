<?php

use common\models\Entity;
use common\models\StatusType;
use yii\helpers\Html;
use yii\web\YiiAsset;

/** @var yii\web\View $this */
/** @var common\models\Request $model */

$this->title = 'Gestão de Pedidos';

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

$requestKind = $model->is_external ? 'Pedido Externo' : 'Pedido Interno';
$requestType = $model->requestType?->description ?? 'Sem tipo';
$priority = $model->priority?->description ?? 'Sem prioridade';
$origin = $model->origin ?: 'Sem origem';
$createdAt = Yii::$app->formatter->asDatetime($model->created_at, 'short');

?>

<div class="request-view">
    <div class="row">

        <div class="col-lg-4 mb-4">
            <div class="card card-primary card-outline shadow-sm h-100">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-1"></i>
                        Resumo
                    </h3>
                </div>

                <div class="card-body">

                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle shadow-sm"
                             style="width: 86px; height: 86px; font-size: 2.2rem;">
                            <i class="fas fa-file-alt text-secondary"></i>
                        </div>

                        <h4 class="mt-3 mb-1">
                            <?= Html::encode($requestKind) ?>
                        </h4>

                        <div class="text-muted small mb-2">
                            <?= Html::encode($requestType) ?>
                        </div>

                        <?= Html::tag('span', Html::encode($statusDescription), [
                            'class' => "badge {$statusClass}",
                        ]) ?>
                    </div>

                    <ul class="list-group list-group-unbordered mb-4">
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <b>Origem</b>
                            <span class="text-muted text-right">
                                <?= Html::encode($origin) ?>
                            </span>
                        </li>

                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <b>Prioridade</b>
                            <span class="text-muted text-right">
                                <?= Html::encode($priority) ?>
                            </span>
                        </li>

                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <b>Criado em</b>
                            <span class="text-muted text-right">
                                <?= $createdAt ?>
                            </span>
                        </li>
                    </ul>

                    <?php if ($isPending): ?>
                        <div class="row">
                            <div class="col-6">
                                <?= Html::a('<i class="fas fa-times mr-1"></i> Negar',
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
                                <?= Html::a('<i class="fas fa-check mr-1"></i> Aceitar',
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

                        <hr>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-8 mb-4">
            <div class="card card-info card-outline shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-align-left mr-1"></i>
                        Detalhes do Pedido
                    </h3>

                    <div class="ml-auto">
                        <?= Html::a('<i class="fas fa-arrow-left"></i>', ['index'], [
                            'class' => 'btn btn-outline-secondary mr-1',
                            'title' => 'Voltar',
                        ]) ?>

                        <?= Html::a('<i class="fas fa-edit"></i>', ['update', 'id' => $model->id], [
                            'class' => 'btn btn-primary',
                            'title' => 'Editar pedido',
                        ]) ?>
                    </div>
                </div>

                <div class="card-body">

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="info-box bg-light shadow-sm mb-0">
                                <span class="info-box-icon bg-info">
                                    <i class="fas fa-tags"></i>
                                </span>

                                <div class="info-box-content">
                                    <span class="info-box-text text-muted">Tipo</span>
                                    <span class="info-box-number">
                                        <?= Html::encode($requestType) ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="info-box bg-light shadow-sm mb-0">
                                <span class="info-box-icon bg-warning">
                                    <i class="fas fa-flag"></i>
                                </span>

                                <div class="info-box-content">
                                    <span class="info-box-text text-muted">Prioridade</span>
                                    <span class="info-box-number">
                                        <?= Html::encode($priority) ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="info-box bg-light shadow-sm mb-0">
                                <span class="info-box-icon bg-secondary">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>

                                <div class="info-box-content">
                                    <span class="info-box-text text-muted">Origem</span>
                                    <span class="info-box-number">
                                        <?= Html::encode($origin) ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="info-box bg-light shadow-sm mb-0">
                                <span class="info-box-icon bg-primary">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>

                                <div class="info-box-content">
                                    <span class="info-box-text text-muted">Criado em</span>
                                    <span class="info-box-number">
                                        <?= $createdAt ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="request-details-section">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-file-alt text-info mr-2"></i>
                            <h5 class="mb-0">Descrição do Pedido</h5>
                        </div>

                        <?php if (!empty($model->details)): ?>
                            <div class="p-4 bg-light rounded border text-break" style="min-height: 220px;">
                                <?= nl2br(Html::encode($model->details)) ?>
                            </div>
                        <?php else: ?>
                            <div class="p-4 bg-light rounded border text-muted" style="min-height: 220px;">
                                Sem detalhes registados.
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>