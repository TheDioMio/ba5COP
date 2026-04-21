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
                        ['index'],
                        ['class' => 'btn btn-sm ' . (empty($status) ? 'btn-primary' : 'btn-outline-primary')]
                    ) ?>

                    <?php foreach ($statuses as $s): ?>
                        <?= Html::a(
                            $s->description,
                            [
                                'index',
                                'status_type_id' => $s->id,
                                'RequestSearchInternos' => Yii::$app->request->get('RequestSearchInternos'),
                                'RequestSearchExternos' => Yii::$app->request->get('RequestSearchExternos'),
                            ],
                            [
                                'class' => 'btn btn-sm ' . ((string)$status === (string)$s->id ? 'btn-primary' : 'btn-outline-primary')
                            ]
                        ) ?>
                    <?php endforeach; ?>
                </div>

                <div class="card-tools">
                    <?= Html::a('<i class="fas fa-plus-circle"></i>', ['create'], [
                        'class' => 'btn btn-success',
                        'title' => 'Criar',
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-body p-0">
            <h6 class="px-3 pt-3 mb-2">Pedidos Internos</h6>

            <?= GridView::widget([
                'dataProvider' => $dataProviderInternos,
                'filterModel' => $searchModelInternos,
                'tableOptions' => ['class' => 'table table-hover table-striped table-sm mb-0'],
                'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [
                    [
                        'attribute' => 'origin',
                        'filter' => false,
                    ],
                    [
                        'attribute' => 'details',
                        'filter' => false,
                    ],
                    [
                        'label' => 'Tipo de Pedido',
                        'attribute' => 'requestType.description',
                        'filter' => false,
                    ],
                    [
                        'attribute' => 'priority_id',
                        'label' => 'Prioridade',
                        'value' => function ($model) {
                            return $model->priority?->description;
                        },
                        'filter' => $priorityList,
                    ],
                    [
                        'attribute' => 'status',
                        'label' => 'Estado',
                        'value' => function ($model) {
                            return $model->statusType?->description;
                        },
                        'filter' => false,
                    ],
                    [
                        'class' => ActionColumn::class,
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
        <div class="card-body p-0">
            <h6 class="px-3 pt-3 mb-2">Pedidos Externos</h6>

            <?= GridView::widget([
                'dataProvider' => $dataProviderExternos,
                'filterModel' => $searchModelExternos,
                'tableOptions' => ['class' => 'table table-hover table-striped table-sm mb-0'],
                'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [
                    [
                        'attribute' => 'origin',
                        'filter' => false,
                    ],
                    [
                        'attribute' => 'details',
                        'filter' => false,
                    ],
                    [
                        'label' => 'Tipo de Pedido',
                        'attribute' => 'requestType.description',
                        'filter' => false,
                    ],
                    [
                        'attribute' => 'priority_id',
                        'label' => 'Prioridade',
                        'value' => function ($model) {
                            return $model->priority?->description;
                        },
                        'filter' => $priorityList,
                    ],
                    [
                        'attribute' => 'status',
                        'label' => 'Estado',
                        'value' => function ($model) {
                            return $model->statusType?->description;
                        },
                        'filter' => false,
                    ],
                    [
                        'class' => ActionColumn::class,
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