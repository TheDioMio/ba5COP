<?php

use common\models\Location;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\LocationSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestão de Localizações';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="location-index container-fluid">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
<!--            <div class="card-tools float-right">-->
<!--                --><?php //= Html::a('<i class="fas fa-plus-circle"></i>',
//                    ['create'],
//                    [
//                        'class' => 'btn btn-success',
//                        'title' => 'Criar',
//                    ])
//                ?>
<!--            </div>-->
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-hover table-striped table-sm'],
                'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [
                    [
                        'label' => 'Tipo',
                        'value' => 'locationType.description',
                    ],
                    'name',
                    [
                        'label' => 'Status',
                        'value' => 'statusType.description',
                    ],
//                    'geometry:ntext',
                    //'updated_at',
                    //'entity_id',
                    [
                        'class' => ActionColumn::className(),
                        'template' => '{delete}',
                        'urlCreator' => function ($action, Location $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>