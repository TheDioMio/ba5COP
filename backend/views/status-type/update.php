<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\StatusType $model */

$this->title = 'Update Status Type: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Status Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="status-type-update container-fluid">
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
                'arrayEntityTypes' => $arrayEntityTypes,
            ]) ?>
        </div>
    </div>
</div>