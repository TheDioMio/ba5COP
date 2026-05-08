<?php

use common\models\Entity;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\EntitySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestão de Entidades';
?>
<div class="entity-index container-fluid">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <div class="card-tools float-right">
            </div>
        </div>
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-hover table-striped table-sm'],
                'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [
                    [
                        'attribute' => 'entity_name',
                        'label' => 'Entidade',
                        'value' => 'entityName',
                        'filter' => Html::activeTextInput($searchModel, 'entity_name', [
                            'class' => 'form-control',
                        ]),
                    ],
                    [
                        'attribute' => 'entity_type_name',
                        'label' => 'Tipo de Entidade',
                        'value' => 'entityType.name',
                        'filter' => $entityTypes,
                    ],
                    [
                        'template' => '{delete}',
                        'class' => ActionColumn::className(),
                        'urlCreator' => function ($action, Entity $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>