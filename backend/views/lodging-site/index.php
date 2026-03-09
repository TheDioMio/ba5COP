<?php

use common\models\LodgingSite;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\LodgingSiteSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestão de Alojamentos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lodging-site-index container-fluid">
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
                    'location_id',
                    'name',
                    [
                        'label' => 'Capacidade',
                        'format' => 'raw',
                        'contentOptions' => ['class' => 'text-center'],
                        'headerOptions' => ['class' => 'text-center'],
                        'value' => function ($model) {

                            $total = (int)$model->capacity_total;
                            $available = (int)$model->capacity_available;
                            $occupied = $total - $available;

                            if ($total === 0) {
                                return '-';
                            }

                            $percent = ($occupied / $total) * 100;

                            if ($percent >= 100) {
                                $class = 'bg-danger';
                            } elseif ($percent >= 80) {
                                $class = 'bg-warning text-dark';
                            } else {
                                $class = 'bg-success';
                            }

                            return "<span class='badge {$class}'>{$occupied}</span> / {$total}";
                        },
                    ],
                    //'notes',
                    [
                        'class' => ActionColumn::className(),
                        'urlCreator' => function ($action, LodgingSite $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>