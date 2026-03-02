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

    <?= $form->field($model, 'decided_at')->textInput() ?>

    <?= $form->field($model, 'decided_by')->textInput() ?>

    <?= $form->field($model, 'entity_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
