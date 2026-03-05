<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\AuditLog $model */

$this->title = 'Atualizar Registo #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Audit Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="audit-log-update container-fluid">
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
                'usersArray' => $usersArray,
                'entitiesArray' => $entitiesArray,
            ]) ?>
        </div>
    </div>
</div>