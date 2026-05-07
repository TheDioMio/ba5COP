<?php

use common\models\AuditLog;
use common\models\Entity;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\AuditLogSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestão do Histórico de Edições';

$users = ArrayHelper::map(User::find()->orderBy('username')->all(), 'id', 'username');
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
                'tableOptions' => ['class' => 'table table-hover table-striped table-sm mb-0'],
                'layout' => "{items}\n{summary}\n{pager}",
                'columns' => [
                    [
                        'attribute' => 'user_username',
                        'label' => 'Utilizador',
                        'value' => fn($model) => $model->user->username ?? 'Utilizador apagado',
                        'filter' => $users,
                    ],
                    [
                        'attribute' => 'entity_name',
                        'label' => 'Entidade',
                        'value' => function ($model) {
                            return $model->entity->entityName ?? 'Apagado! Entity ID: ' . $model->entity_id;
                        },
                        'filter' => Html::activeTextInput($searchModel, 'entity_name', [
                            'class' => 'form-control',
                        ]),
                    ],
                    [
                        'attribute' => 'action',
                        'label' => 'Ação',
                    ],
                    [
                        'attribute' => 'occurred_at',
                        'label' => 'Data/Hora',
                    ],
                    [
                        'template' => '{delete}',
                        'class' => ActionColumn::class,
                        'urlCreator' => function ($action, AuditLog $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        },
                        'contentOptions' => ['style' => 'width: 60px; text-align: center;'],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>