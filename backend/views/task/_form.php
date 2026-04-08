<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Task $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'location_id')
        ->dropDownList($locationsArray, ['prompt' => '-- LOCALIZAÇÃO --'])
        ->label('Localização') ?>

    <?= $form->field($model, 'incident_id')
        ->dropDownList($incidentsArray, ['prompt' => '-- INCIDENTE --'])
        ->label('Incidente') ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'priority_id')
        ->dropDownList($prioritiesArray, ['prompt' => '-- PRIORIDADE --'])
        ->label('Prioridade') ?>

    <?= $form->field($model, 'status_type_id')
        ->dropDownList($statusArray, ['prompt' => '-- STATUS --'])
        ->label('Status') ?>

    <?= $form->field($model, 'assigned_to')
        ->dropDownList($usersArray, ['prompt' => '-- ENTREGUE A --'])
        ->label('Entregue a') ?>

    <?= $form->field($model, 'created_by')
        ->dropDownList($usersArray, ['prompt' => '-- CRIADO POR --'])
        ->label('Criado por') ?>

    <?= $form->field($model, 'created_at')->input('date')?>

    <?= $form->field($model, 'due_at')->input('date') ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
