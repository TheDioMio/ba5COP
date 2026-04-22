<?php

use common\models\LocationType;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\LocationTypeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestão Tipo de Localizações';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="location-type-index container-fluid">
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
                        'urlCreator' => function ($action, LocationType $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
