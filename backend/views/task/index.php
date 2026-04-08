<?php

use common\models\Task;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\TaskSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestão de Tarefas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index container-fluid">
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
                    'id',
                    'location_id',
                    'incident_id',
                    'title',
                    'description',
                    //'priority_id',
                    //'status_type_id',
                    //'assigned_to',
                    //'created_by',
                    //'created_at',
                    //'due_at',
                    //'entity_id',
                    [
                        'class' => ActionColumn::className(),
                        'contentOptions' => ['style' => 'width: 100px; text-align: center;'],
                        'urlCreator' => function ($action, Task $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
