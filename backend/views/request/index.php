<?php

use common\models\Request;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProviderInternos */
/** @var yii\data\ActiveDataProvider $dataProviderExternos */
/** @var string|null $status */

$this->title = 'Gestão de Pedidos';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="request-index container-fluid">

    <div class="card card-outline card-primary shadow-sm mb-3">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <?= Html::a(
                        'Todos',
                        [
                            'index',
                            'priority_internal' => $priorityInternal,
                            'priority_external' => $priorityExternal,
                        ],
                        [
                            'class' => 'btn btn-sm ' . (empty($status) ? 'btn-primary' : 'btn-outline-primary')
                        ]
                    ) ?>

                    <?php foreach ($statuses as $s): ?>
                        <?= Html::a(
                            $s->description,
                            [
                                'index',
                                'status' => $s->id,
                                'priority_internal' => $priorityInternal,
                                'priority_external' => $priorityExternal,
                            ],
                            [
                                'class' => 'btn btn-sm ' . ((string)$status === (string)$s->id ? 'btn-primary' : 'btn-outline-primary')
                            ]
                        ) ?>
                    <?php endforeach; ?>
                </div>

                <div class="card-tools">
                    <?= Html::a('<i class="fas fa-plus-circle"></i>',
                        ['create'],
                        [
                            'class' => 'btn btn-success',
                            'title' => 'Criar',
                        ])
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <span>Pedidos Internos</span>

                <form method="get" class="mb-0">
                    <input type="hidden" name="status" value="<?= Html::encode($status) ?>">
                    <input type="hidden" name="priority_external" value="<?= Html::encode($priorityExternal) ?>">

                    <select name="priority_internal" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">Todas as prioridades</option>
                        <?php foreach ($priorities as $priority): ?>
                            <option value="<?= $priority->id ?>" <?= ((string)$priorityInternal === (string)$priority->id) ? 'selected' : '' ?>>
                                <?= Html::encode($priority->description) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
        </div>

        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProviderInternos,
                'tableOptions' => ['class' => 'table table-hover table-striped table-sm'],
                'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [
                    'origin',
                    'details',
                    [
                        'attribute' => 'priority_id',
                        'label' => 'Prioridade',
                        'value' => function ($model) {
                            return $model->priority ? $model->priority->description : null;
                        },
                    ],
                    [
                        'attribute' => 'status',
                        'label' => 'Estado',
                        'value' => function ($model) {
                            return $model->status ? $model->status->description : null;
                        },
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'urlCreator' => function ($action, Request $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
                'emptyText' => 'Não existem pedidos internos.',
            ]); ?>
        </div>
    </div>

    <div class="card card-outline card-secondary shadow-sm">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <span>Pedidos Externos</span>

                <form method="get" class="mb-0">
                    <input type="hidden" name="status" value="<?= Html::encode($status) ?>">
                    <input type="hidden" name="priority_internal" value="<?= Html::encode($priorityInternal) ?>">

                    <select name="priority_external" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">Todas as prioridades</option>
                        <?php foreach ($priorities as $priority): ?>
                            <option value="<?= $priority->id ?>" <?= ((string)$priorityExternal === (string)$priority->id) ? 'selected' : '' ?>>
                                <?= Html::encode($priority->description) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
        </div>

        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProviderExternos,
                'tableOptions' => ['class' => 'table table-hover table-striped table-sm'],
                'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [
                    'origin',
                    'details',
                    [
                        'attribute' => 'priority_id',
                        'label' => 'Prioridade',
                        'value' => function ($model) {
                            return $model->priority ? $model->priority->description : null;
                        },
                    ],
                    [
                        'attribute' => 'status',
                        'label' => 'Estado',
                        'value' => function ($model) {
                            return $model->statusType ? $model->statusType->description : null;
                        },
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'urlCreator' => function ($action, Request $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
                'emptyText' => 'Não existem pedidos externos.',
            ]); ?>
        </div>
    </div>

</div>