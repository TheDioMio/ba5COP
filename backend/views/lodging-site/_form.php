<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\LodgingSite $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="lodging-site-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'location_id')->dropDownList(
        $arraySites,
        ['prompt' => '-- LOCALIZAÇÕES --']
    )->label('Localização') ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'capacity_total')->input('number', ['min' => 1]) ?>

    <?= $form->field($model, 'notes')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
