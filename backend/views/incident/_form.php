<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Incident $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="incident-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'location_id')->textInput() ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'incident_type_id')->textInput() ?>

    <?= $form->field($model, 'priority_id')->textInput() ?>

    <?= $form->field($model, 'status_type_id')->textInput() ?>

    <?= $form->field($model, 'reported_by')->textInput() ?>

    <?= $form->field($model, 'entity_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
