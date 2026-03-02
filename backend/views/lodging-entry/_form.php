<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\LodgingEntry $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="lodging-entry-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'lodging_site_id')->textInput() ?>

    <?= $form->field($model, 'branch_id')->textInput() ?>

    <?= $form->field($model, 'people_count')->textInput() ?>

    <?= $form->field($model, 'checkin_at')->textInput() ?>

    <?= $form->field($model, 'checkout_at')->textInput() ?>

    <?= $form->field($model, 'notes')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
