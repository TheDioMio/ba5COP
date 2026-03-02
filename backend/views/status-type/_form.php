<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\StatusType $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="status-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'entity_type_id')->textInput() ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
