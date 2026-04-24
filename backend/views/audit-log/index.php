<?php

use common\models\AuditLog;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\AuditLogSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestão do Histórico de Edições';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audit-log-index container-fluid">
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
                    [
                        'label' => 'Utilizador',
                        'attribute' => 'user_username',
                        'value' => 'user.username',
                    ],
                    [
                        'label' => 'Entidade',
                        'value' => function ($model) {
                            return $model->entity->entityName ?? 'Apagado! Entity ID: ' . $model->entity_id;
                        },
                    ],
                    'action',
                    'occurred_at',
                    [
                        'template' => '{delete}',
                        'class' => ActionColumn::className(),
                        'urlCreator' => function ($action, AuditLog $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        }
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>