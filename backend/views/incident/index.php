<?php

use common\models\Incident;
use common\models\IncidentType;
use common\models\Location;
use common\models\Priority;
use common\models\StatusType;
use common\models\Task;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\IncidentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestão de Incidentes';
$this->params['breadcrumbs'][] = $this->title;

$incidentTypes = ArrayHelper::map(IncidentType::find()->orderBy('description')->all(), 'id', 'description');
$priorities = ArrayHelper::map(Priority::find()->orderBy('id')->all(), 'id', 'description');
$statuses = ArrayHelper::map(StatusType::find()->orderBy('description')->all(), 'id', 'description');
$users = ArrayHelper::map(User::find()->orderBy('username')->all(), 'id', 'username');
?>

    <div class="incident-index container-fluid">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header">
                <div class="card-tools float-right">
                    <?= Html::a(
                        '<i class="fas fa-plus-circle"></i>',
                        ['create'],
                        [
                            'class' => 'btn btn-success',
                            'title' => 'Criar',
                        ]
                    ) ?>
                </div>
            </div>

            <div class="card-body p-0">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'tableOptions' => ['class' => 'table table-hover table-striped table-sm mb-0'],
                    'layout' => "{items}\n{summary}\n{pager}",
                    'rowOptions' => fn($model) => ['class' => 'incident-row'],
                    'columns' => [
                        [
                            'attribute' => 'title',
                            'label' => 'Incidente',
                        ],
                        [
                            'attribute' => 'incident_type_id',
                            'label' => 'Tipo',
                            'value' => fn($model) => $model->incidentType->description ?? null,
                            'filter' => $incidentTypes,
                        ],
                        [
                            'attribute' => 'priority_id',
                            'label' => 'Prioridade',
                            'value' => fn($model) => $model->priority->description ?? null,
                            'filter' => $priorities,
                            'contentOptions' => ['style' => 'width: 90px; text-align: center;'],
                        ],
                        [
                            'attribute' => 'status_type_id',
                            'label' => 'Estado',
                            'value' => fn($model) => $model->statusType->description ?? null,
                            'filter' => $statuses,
                            'contentOptions' => ['style' => 'width: 120px; text-align: center;'],
                        ],
                        [
                            'attribute' => 'location_name',
                            'label' => 'Local',
                            'value' => fn($model) => $model->location->name ?? null,
                            'filter' => Html::activeTextInput($searchModel, 'location_name', [
                                'class' => 'form-control',
                                'placeholder' => 'Pesquisar local',
                            ]),
                            'format' => 'raw',
                        ],
                        [
                            'label' => 'Nº tarefas',
                            'value' => fn($model) => count($model->tasks),
                            'contentOptions' => ['style' => 'width: 90px; text-align: center;'],
                        ],
                        [
                            'label' => 'Tarefas abertas',
                            'value' => function ($model) {
                                return count(array_filter($model->tasks, function ($task) {
                                    return $task->status_type_id != 10;
                                }));
                            },
                            'contentOptions' => ['style' => 'width: 120px; text-align: center;'],
                        ],
                        [
                            'attribute' => 'task_title',
                            'label' => 'Pesquisar tarefa',
                            'value' => function ($model) {
                                $titles = ArrayHelper::getColumn($model->tasks, 'title');

                                if (empty($titles)) {
                                    return '-';
                                }

                                $preview = implode(', ', array_slice($titles, 0, 2));

                                return count($titles) > 2 ? $preview . '...' : $preview;
                            },
                            'filter' => Html::activeTextInput($searchModel, 'task_title', [
                                'class' => 'form-control',
                                'placeholder' => 'Título da tarefa',
                            ]),
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'assigned_to',
                            'label' => 'Responsável',
                            'value' => function ($model) {
                                $taskUsers = [];

                                foreach ($model->tasks as $task) {
                                    if ($task->assignedTo) {
                                        $taskUsers[$task->assignedTo->id] = $task->assignedTo->username;
                                    }
                                }

                                return empty($taskUsers) ? '-' : implode(', ', $taskUsers);
                            },
                            'filter' => $users,
                        ],
                        [
                            'label' => 'Detalhe',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return Html::button('Ver tarefas', [
                                    'class' => 'btn btn-xs btn-primary toggle-incident-detail',
                                    'data-target' => '#incident-detail-' . $model->id,
                                ]);
                            },
                            'contentOptions' => ['style' => 'width: 110px; text-align: center;'],
                        ],
                        [
                            'class' => ActionColumn::class,
                            'urlCreator' => function ($action, Incident $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'id' => $model->id]);
                            },
                        ],
                    ],
                    'afterRow' => function ($model, $key, $index, $grid) {
                        $tasksProvider = new ActiveDataProvider([
                            'query' => Task::find()
                                ->where(['incident_id' => $model->id])
                                ->with(['priority', 'statusType', 'assignedTo']),
                            'pagination' => false,
                        ]);

                        return '<tr id="incident-detail-' . $model->id . '" class="incident-detail-row" style="display:none;">'
                            . '<td colspan="10" class="p-0">'
                            . '<div class="m-2 p-3 border rounded bg-light">'
                            . '<div class="d-flex justify-content-between align-items-start mb-3">'
                            . '<div class="ms-auto">'
                            . Html::a(
                                '<i class="fas fa-plus-circle"></i>',
                                ['task/create', 'incident_id' => $model->id],
                                [
                                    'class' => 'btn btn-success btn-xs',
                                    'title' => 'Criar tarefa',
                                ]
                            )
                            . '</div>'
                            . '<div class="text-right">'
                            . '</div>'
                            . '</div>'
                            . GridView::widget([
                                'dataProvider' => $tasksProvider,
                                'summary' => '',
                                'tableOptions' => ['class' => 'table table-hover table-striped table-sm mb-0'],
                                'layout' => "{items}",
                                'columns' => [
                                    'title',
                                    [
                                        'attribute' => 'priority_id',
                                        'label' => 'Prioridade',
                                        'value' => fn($task) => $task->priority->description ?? null,
                                        'contentOptions' => ['style' => 'width: 90px; text-align: center;'],
                                    ],
                                    [
                                        'attribute' => 'status_type_id',
                                        'label' => 'Estado',
                                        'value' => fn($task) => $task->statusType->description ?? null,
                                        'contentOptions' => ['style' => 'width: 120px; text-align: center;'],
                                    ],
                                    [
                                        'attribute' => 'assigned_to',
                                        'label' => 'Responsável',
                                        'value' => fn($task) => $task->assignedTo->username ?? null,
                                    ],
                                    [
                                        'attribute' => 'due_at',
                                        'label' => 'Prazo',
                                        'contentOptions' => ['style' => 'width: 110px; text-align: center;'],
                                    ],
                                    [
                                        'label' => 'Ações',
                                        'format' => 'raw',
                                        'value' => function ($task) {
                                            $buttons = Html::a(
                                                'Abrir',
                                                ['/task/view', 'id' => $task->id],
                                                ['class' => 'btn btn-xs btn-secondary mr-1']
                                            );

                                            $buttons .= Html::a(
                                                'Editar',
                                                ['/task/update', 'id' => $task->id],
                                                ['class' => 'btn btn-xs btn-warning mr-1']
                                            );

                                            if ($task->status_type_id == StatusType::STATUS_TASK_NEW) {
                                                $buttons .= Html::a(
                                                    'Iniciar',
                                                    ['/task/change-status', 'id' => $task->id],
                                                    [
                                                        'class' => 'btn btn-xs btn-primary',
                                                        'data' => [
                                                            'method' => 'post',
                                                            'confirm' => 'Passar esta tarefa para DOING?',
                                                        ],
                                                    ]
                                                );
                                            } elseif ($task->status_type_id == StatusType::STATUS_TASK_DOING) {
                                                $buttons .= Html::a(
                                                    'Concluir',
                                                    ['/task/change-status', 'id' => $task->id],
                                                    [
                                                        'class' => 'btn btn-xs btn-success',
                                                        'data' => [
                                                            'method' => 'post',
                                                            'confirm' => 'Marcar esta tarefa como DONE?',
                                                        ],
                                                    ]
                                                );
                                            }

                                            return $buttons;
                                        },
                                        'contentOptions' => ['style' => 'width: 220px; text-align: center;'],
                                    ],
                                ],
                            ])
                            . '</div>'
                            . '</td>'
                            . '</tr>';
                    },
                ]); ?>
            </div>
        </div>
    </div>

<?php
$js = <<<JS
document.addEventListener('click', function(e) {
    const button = e.target.closest('.toggle-incident-detail');
    if (!button) return;

    const target = document.querySelector(button.dataset.target);
    if (!target) return;

    const isVisible = target.style.display !== 'none';
    target.style.display = isVisible ? 'none' : 'table-row';
    button.textContent = isVisible ? 'Ver tarefas' : 'Ocultar tarefas';
});
JS;
$this->registerJs($js);
?>