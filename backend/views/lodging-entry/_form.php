<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\LodgingEntry $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="lodging-entry-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'lodging_site_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'unit_id')
        ->dropDownList($unitArray, ['prompt' => '-- UNIDADE --'])
        ->label('Unidade') ?>

    <?= $form->field($model, 'people_count')->input('number', ['min' => 1]) ?>

    <?= $form->field($model, 'checkin_at')->input('date') ?>

    <?= $form->field($model, 'notes')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
