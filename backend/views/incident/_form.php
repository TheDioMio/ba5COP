<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Incident $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="incident-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'incident_type_id')
        ->dropDownList($arrayIncidentTypes, ['prompt' => '-- TIPO DE INCIDENTE --'])
        ->label('Tipo de Incidente') ?>

    <?= $form->field($model, 'location_id')
        ->dropDownList($arrayLocations, ['prompt' => '-- LOCALIZAÇÃO --'])
        ->label('Localização') ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'priority_id')
        ->dropDownList($arrayPriorities, ['prompt' => '-- PRIORIDADE --'])
        ->label('Prioridade') ?>

    <?= $form->field($model, 'status_type_id')
        ->dropDownList($arrayStatus, ['prompt' => '-- STATUS --'])
        ->label('Status') ?>

    <?= $form->field($model, 'reported_by')
        ->dropDownList($arrayUsers, ['prompt' => '-- UTILIZADOR --'])
        ->label('Reportado por?') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
