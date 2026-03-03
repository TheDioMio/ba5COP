<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestão de Utilizadores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index container-fluid">
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
                    'username',
                    'email:email',
                    [
                        'label' => 'Permissões',
                        'attribute' => 'role_description',
                        'value' => 'role.description',
                    ],
                    [
                        'label' => 'Status',
                        'attribute' => 'status',
                        'format' => 'raw', //Tem que ser raw, para os botões serem renderizados (Botões HTML)
                        'value' => function ($model) use ($userLogado) {
                            if($model->id == $userLogado->id) {
                                //Se a conta for a conta logada, não dá para desativar nem ativar
                                return Html::a('Ativo (Tu)', '#', [
                                    'class' => 'btn btn-xs btn-success btn-block disabled',
                                    'onclick' => 'return false;', // Para não fazer nada se clicar
                                    'style' => 'opacity: 0.7;'
                                ]);
                            }
                            if ($model->status == 10 && $model->id != $userLogado->id) {
                                //Se for ativo (10), mostra o botão verde, e ao clicar desativa
                                return Html::a('Ativo', ['update-status', 'id' => $model->id], [
                                    'class' => 'btn btn-xs btn-success btn-block',
                                    'data' => [
                                        'method' => 'post',
                                    ],
                                ]);
                            } else if ($model->status == 9 && $model->id != $userLogado->id){
                                //Se for inativo (10), mostra o botão vermelho, e ao clicar ativa
                                return Html::a('Inativo', ['update-status', 'id' => $model->id], [
                                    'class' => 'btn btn-xs btn-secondary btn-danger btn-block',
                                    'data' => [
                                        'method' => 'post',
                                    ],
                                ]);
                            } else {
                                return Html::a('Erro: STATUS INVÁLIDO', '#', [
                                    'class' => 'btn btn-xs btn-red btn-block',
                                    'onclick' => 'return false',
                                ]);
                            }
                        },
                        'filter' => [
                            10 => 'Ativo',
                            9  => 'Inativo',
                        ],
                        'contentOptions' => ['style' => 'width: 100px; text-align: center;'],
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'urlCreator' => function ($action, User $model) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        },
                        //Esconde o botão de delete no próprio user.
                        'visibleButtons' => [
                            'delete' => function ($model) use ($userLogado) {
                                if($model->id != $userLogado->id){
                                    return $model->id;
                                }
                                return null;
                            },
                            // Podes fazer o mesmo para 'update' ou 'view' se precisares
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>