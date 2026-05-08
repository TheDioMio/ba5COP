<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\IncidentType $model */

$this->title = 'Gestão do Tipo de Incidentes';
?>
<div class="incident-type-update container-fluid">
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
            ]) ?>
        </div>
    </div>
</div>