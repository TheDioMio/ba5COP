<?php

use common\models\RequestType;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\RequestTypeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestão Tipo de Pedidos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-type-index container-fluid">
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
                'layout' => "{items}\n{summary}\n{pager}",
                'tableOptions' => ['class' => 'table table-hover table-striped table-sm'],
                'columns' => [
                    'description',
                    [
                        'class' => ActionColumn::className(),
                        'template' => '{update} {delete}',
                        'urlCreator' => function ($action, RequestType $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
