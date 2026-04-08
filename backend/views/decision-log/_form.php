<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\DecisionLog $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="decision-log-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'reason')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'impact')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'decided_at')->input('date')?>

    <?= $form->field($model, 'decided_by')
        ->dropDownList($usersArray, ['prompt' => '-- DECIDIDO POR --'])
        ->label('Decidido por') ?>

    <?= $form->field($model, 'status_type_id')
        ->dropDownList($statusArray, ['prompt' => '-- STATUS --'])
        ->label('Status') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
