<?php

use common\models\Entity;
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

$incidentTypes = ArrayHelper::map(IncidentType::find()->orderBy('description')->all(), 'id', 'description');
$priorities = ArrayHelper::map(Priority::find()->orderBy('id')->all(), 'id', 'description');
$statuses = StatusType::getStatusDropdown(Entity::INCIDENT_ID);
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
                            ]),
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'tasks_count',
                            'label' => 'Nº tarefas',
                            'value' => fn($model) => $model->tasks_count ?? 0,
                            'filter' => false,
                            'contentOptions' => ['style' => 'width: 90px; text-align: center;'],
                        ],
                        [
                            'attribute' => 'open_tasks_count',
                            'label' => 'Tarefas abertas',
                            'value' => fn($model) => $model->open_tasks_count ?? 0,
                            'filter' => false,
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
                            'label' => ' ',
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
                            'header' => 'Ações',
                            'template' => '{done} {update} {delete}',
                            'class' => ActionColumn::class,
                            'contentOptions' => [
                                'class' => 'incident-actions-cell',
                            ],
                            'buttons' => [
                                'done' => function ($url, $model, $key) {
                                    if ($model->status_type_id == StatusType::STATUS_INCIDENT_RESOLVED) {
                                        return Html::tag(
                                            'span',
                                            '<i class="fas fa-check"></i>',
                                            [
                                                'class' => 'incident-action-icon incident-action-finished',
                                                'title' => 'Incidente já terminado',
                                            ]
                                        );
                                    }

                                    return Html::a(
                                        '<i class="far fa-check-circle"></i>',
                                        ['/incident/done', 'id' => $model->id],
                                        [
                                            'class' => 'incident-action-icon incident-action-done',
                                            'title' => 'Marcar incidente como terminado',
                                            'data' => [
                                                'method' => 'post',
                                                'confirm' => 'Marcar este incidente como terminado?',
                                            ],
                                        ]
                                    );
                                },

                                'update' => function ($url, $model, $key) {
                                    return Html::a(
                                        '<i class="fas fa-pen"></i>',
                                        ['/incident/update', 'id' => $model->id],
                                        [
                                            'class' => 'incident-action-icon incident-action-edit',
                                            'title' => 'Editar incidente',
                                        ]
                                    );
                                },

                                'delete' => function ($url, $model, $key) {
                                    return Html::a(
                                        '<i class="fas fa-trash-alt"></i>',
                                        ['/incident/delete', 'id' => $model->id],
                                        [
                                            'class' => 'incident-action-icon incident-action-delete',
                                            'title' => 'Apagar incidente',
                                            'data' => [
                                                'method' => 'post',
                                                'confirm' => 'Tens a certeza que queres apagar este incidente?',
                                            ],
                                        ]
                                    );
                                },
                            ],
                        ],
                    ],
                    'afterRow' => function ($model, $key, $index, $grid) {
                        $incidentIsDone = $model->status_type_id == StatusType::STATUS_INCIDENT_RESOLVED;

                        $tasksProvider = new ActiveDataProvider([
                            'query' => Task::find()
                                ->where(['incident_id' => $model->id])
                                ->with(['priority', 'statusType', 'assignedTo']),
                            'pagination' => false,
                        ]);

                        $createTaskButton = $incidentIsDone
                            ? Html::tag(
                                'span',
                                '<i class="fas fa-lock"></i>',
                                [
                                    'class' => 'incident-task-create-locked',
                                    'title' => 'Incidente terminado — não é possível criar tarefas',
                                ]
                            )
                            : Html::a(
                                '<i class="fas fa-plus-circle"></i>',
                                ['task/create', 'incident_id' => $model->id],
                                [
                                    'class' => 'btn btn-success btn-xs',
                                    'title' => 'Criar tarefa',
                                ]
                            );

                        return '<tr id="incident-detail-' . $model->id . '" class="incident-detail-row" style="display:none;">'
                            . '<td colspan="11" class="p-0">'
                            . '<div class="m-2 p-3 border rounded bg-light">'
                            . '<div class="d-flex justify-content-between align-items-start mb-3">'
                            . '<div>'
                            . ($incidentIsDone
                                ? '<span class="incident-locked-info"><i class="fas fa-lock"></i> Incidente terminado — tarefas bloqueadas</span>'
                                : ''
                            )
                            . '</div>'
                            . '<div class="ms-auto">'
                            . $createTaskButton
                            . '</div>'
                            . '</div>'
                            . GridView::widget([
                                'dataProvider' => $tasksProvider,
                                'summary' => '',
                                'tableOptions' => ['class' => 'table table-hover table-striped table-sm mb-0 incident-task-table'],
                                'layout' => "{items}",
                                'columns' => [
                                    [
                                        'attribute' => 'title',
                                        'label' => 'Tarefa',
                                    ],
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
                                        'value' => function ($task) use ($incidentIsDone) {
                                            $buttons = Html::a(
                                                '<i class="fas fa-eye"></i>',
                                                ['/task/view', 'id' => $task->id],
                                                [
                                                    'class' => 'incident-task-action incident-task-view',
                                                    'title' => 'Ver tarefa',
                                                ]
                                            );

                                            if ($incidentIsDone) {
                                                $buttons .= Html::tag(
                                                    'span',
                                                    '<i class="fas fa-pen"></i>',
                                                    [
                                                        'class' => 'incident-task-action incident-task-disabled',
                                                        'title' => 'Incidente terminado — não é possível editar tarefas',
                                                    ]
                                                );

                                                $buttons .= Html::tag(
                                                    'span',
                                                    '<i class="fas fa-check"></i>',
                                                    [
                                                        'class' => 'incident-task-action incident-task-disabled',
                                                        'title' => 'Incidente terminado — não é possível alterar o estado da tarefa',
                                                    ]
                                                );

                                                return $buttons;
                                            }

                                            $buttons .= Html::a(
                                                '<i class="fas fa-pen"></i>',
                                                ['/task/update', 'id' => $task->id],
                                                [
                                                    'class' => 'incident-task-action incident-task-edit',
                                                    'title' => 'Editar tarefa',
                                                ]
                                            );

                                            if ($task->status_type_id == StatusType::STATUS_TASK_NEW) {
                                                $buttons .= Html::a(
                                                    '<i class="fas fa-play"></i>',
                                                    ['/task/change-status', 'id' => $task->id],
                                                    [
                                                        'class' => 'incident-task-action incident-task-start',
                                                        'title' => 'Iniciar tarefa',
                                                        'data' => [
                                                            'method' => 'post',
                                                            'confirm' => 'Passar esta tarefa para em execução?',
                                                        ],
                                                    ]
                                                );
                                            } elseif ($task->status_type_id == StatusType::STATUS_TASK_DOING) {
                                                $buttons .= Html::a(
                                                    '<i class="fas fa-check"></i>',
                                                    ['/task/change-status', 'id' => $task->id],
                                                    [
                                                        'class' => 'incident-task-action incident-task-done',
                                                        'title' => 'Concluir tarefa',
                                                        'data' => [
                                                            'method' => 'post',
                                                            'confirm' => 'Marcar esta tarefa como concluída?',
                                                        ],
                                                    ]
                                                );
                                            } else {
                                                $buttons .= Html::tag(
                                                    'span',
                                                    '<i class="fas fa-check"></i>',
                                                    [
                                                        'class' => 'incident-task-action incident-task-finished',
                                                        'title' => 'Tarefa já concluída',
                                                    ]
                                                );
                                            }

                                            return $buttons;
                                        },
                                        'contentOptions' => ['style' => 'width: 130px; text-align: center;'],
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