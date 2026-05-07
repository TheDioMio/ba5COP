<?php

use common\models\LodgingSite;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\LodgingSiteSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestão de Alojamentos';
?>

<div class="lodging-site-index container-fluid">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
        </div>

        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-hover table-striped table-sm'],
                'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [
                    'name',
                    [
                        'attribute' => 'capacity_total',
                        'label' => 'Capacidade Total',
                    ],
                    [
                        'attribute' => 'capacity_available',
                        'label' => 'Capacidade Disponível',
                    ],
                    [
                        'class' => ActionColumn::class,
                        'urlCreator' => function ($action, LodgingSite $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>