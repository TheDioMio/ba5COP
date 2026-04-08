<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Task $model */

$this->title = 'Gestão de Tarefas';
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="task-update container-fluid">
    <div class="card card-outline card-success shadow-sm">
        <div class="card-header">
            <div class="card-tools">
                <?= Html::button('<i class="fas fa-arrow-left"></i>', [
                    'class' => 'btn btn-default',
                    'onclick' => 'history.back();',
                ]) ?>
            </div>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
                'locationsArray' => $locationsArray,
                'incidentsArray' => $incidentsArray,
                'prioritiesArray' => $prioritiesArray,
                'statusArray' => $statusArray,
                'usersArray' => $usersArray,
            ]) ?>
        </div>
    </div>
</div>
