<?php

use common\models\DecisionLog;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\DecisionLogSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestão do Log de Decisões';
?>
<div class="decision-log-index container-fluid">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <div class="card-tools float-right">
                <?= Html::a('<i class="fas fa-plus-circle"></i>',
                    ['create'],
                    [
                        'class' => 'btn btn-success',
                        'title' => 'Criar',
                    ])
                ?>
            </div>
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-hover table-striped table-sm'],
                'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [
                    'reason',
                    [
                        'attribute' => 'decided_at',
                        'label' => 'Data/Hora',
                        'filter' => Html::tag('div',
                            Html::activeInput('date', $searchModel, 'decided_date', [
                                'class' => 'form-control',
                                'title' => 'Filtrar por data',
                                'style' => 'min-width: 135px;',
                            ]) .
                            Html::activeInput('time', $searchModel, 'decided_time', [
                                'class' => 'form-control',
                                'title' => 'Filtrar por hora',
                                'style' => 'min-width: 90px;',
                            ]),
                            [
                                'style' => 'display: flex; gap: 6px; align-items: center;',
                            ]
                        ),
                    ],
                    [
                        'label' => 'Decidido por',
                        'attribute' => 'decided_by',
                        'value' => 'decidedBy.username'
                    ],
                    [
                        'template' => '{update} {delete}',
                        'class' => ActionColumn::className(),
                        'urlCreator' => function ($action, DecisionLog $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
