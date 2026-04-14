<?php

use common\models\StatusType;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Task $model */

$this->title = 'Tarefa ' . $model->title;
\yii\web\YiiAsset::register($this);

$priority = $model->priority->description ?? '—';
$status = $model->statusType->description ?? '—';

$priorityBadgeClass = match ($priority) {
    'A' => 'badge badge-danger',
    'B' => 'badge badge-warning',
    'C' => 'badge badge-info',
    default => 'badge badge-secondary',
};

$statusBadgeClass = match ($status) {
    'NOVO' => 'badge badge-primary',
    'A FAZER' => 'badge badge-warning',
    'FEITO' => 'badge badge-success',
    default => 'badge badge-secondary',
};
?>

<div class="task-view container-fluid">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header d-flex justify-content-end">
            <div class="card-tools">
                <?= Html::a(
                    '<i class="fas fa-arrow-left"></i>',
                    ['incident/index'],
                    [
                        'class' => 'btn btn-outline-secondary btn-sm mr-1',
                        'title' => 'Voltar',
                    ]
                ) ?>

                <?php if ($model->status_type_id == StatusType::STATUS_TASK_NEW): ?>
                    <?= Html::a(
                        '<i class="fas fa-play"></i>',
                        ['change-status', 'id' => $model->id],
                        [
                            'class' => 'btn btn-info btn-sm mr-1',
                            'title' => 'Iniciar tarefa',
                            'data' => [
                                'method' => 'post',
                                'confirm' => 'Passar esta tarefa para DOING?',
                            ],
                        ]
                    ) ?>
                <?php elseif ($model->status_type_id == StatusType::STATUS_TASK_DOING): ?>
                    <?= Html::a(
                        '<i class="fas fa-check"></i>',
                        ['change-status', 'id' => $model->id],
                        [
                            'class' => 'btn btn-success btn-sm mr-1',
                            'title' => 'Concluir tarefa',
                            'data' => [
                                'method' => 'post',
                                'confirm' => 'Marcar esta tarefa como DONE?',
                            ],
                        ]
                    ) ?>
                <?php endif; ?>

                <?= Html::a(
                    '<i class="fas fa-edit"></i>',
                    ['update', 'id' => $model->id],
                    [
                        'class' => 'btn btn-primary btn-sm mr-1',
                        'title' => 'Editar',
                    ]
                ) ?>

                <?= Html::a(
                    '<i class="fas fa-trash"></i>',
                    ['delete', 'id' => $model->id],
                    [
                        'class' => 'btn btn-danger btn-sm',
                        'title' => 'Apagar',
                        'data' => [
                            'confirm' => 'Tem a certeza que deseja apagar esta tarefa?',
                            'method' => 'post',
                        ],
                    ]
                ) ?>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="small-box bg-light border">
                        <div class="inner">
                            <h5 class="mb-1">Prioridade</h5>
                            <span class="<?= $priorityBadgeClass ?>" style="font-size: 0.95rem;">
                                <?= Html::encode($priority) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small-box bg-light border">
                        <div class="inner">
                            <h5 class="mb-1">Estado</h5>
                            <span class="<?= $statusBadgeClass ?>" style="font-size: 0.95rem;">
                                <?= Html::encode($status) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small-box bg-light border">
                        <div class="inner">
                            <h5 class="mb-1">Responsável</h5>
                            <p class="mb-0"><?= Html::encode($model->assignedTo->username ?? '—') ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="small-box bg-light border">
                        <div class="inner">
                            <h5 class="mb-1">Prazo</h5>
                            <p class="mb-0">
                                <?= $model->due_at ? Yii::$app->formatter->asDate($model->due_at, 'php:d/m/Y') : '—' ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Detalhes</h3>
                </div>
                <div class="card-body p-0">
                    <?= DetailView::widget([
                        'model' => $model,
                        'options' => ['class' => 'table table-striped table-bordered detail-view mb-0'],
                        'attributes' => [
                            [
                                'label' => 'Título',
                                'value' => $model->title,
                            ],
                            [
                                'label' => 'Descrição',
                                'format' => 'ntext',
                                'value' => $model->description,
                            ],
                            [
                                'label' => 'Incidente associado',
                                'format' => 'raw',
                                'value' => $model->incident
                                    ? Html::a(Html::encode($model->incident->title),
                                        ['incident/view', 'id' => $model->incident->id]
                                    )
                                    : '—',
                            ],
                            [
                                'label' => 'Local',
                                'format' => 'raw',
                                'value' => Html::a(Html::encode($model->location->name),
                                        ['location/view', 'id' => $model->location->id]
                                    ) ?? '—',
                            ],
                            [
                                'label' => 'Criada por',
                                'value' => $model->createdBy->username ?? '—',
                            ],
                            [
                                'label' => 'Criada em',
                                'value' => $model->created_at
                                    ? strtoupper(Yii::$app->formatter->asDatetime($model->created_at, 'php:dMY H:i'))
                                    : '—',
                            ],
                            [
                                'label' => 'Prazo',
                                'value' => $model->due_at
                                    ? strtoupper(Yii::$app->formatter->asDate($model->due_at, 'php:dMY'))
                                    : '—',
                            ],
                            [
                                'label' => 'Motivos de bloqueio',
                                'value' => $model->block_reason ?: '—',
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>