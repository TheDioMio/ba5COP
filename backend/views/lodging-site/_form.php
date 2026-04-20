<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\LodgingSite $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="lodging-site-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput([
        'maxlength' => true,
        'placeholder' => 'Ex.: Bloco A'
    ]) ?>

    <?= $form->field($model, 'capacity_total')->input('number', [
        'min' => 0,
        'step' => 1
    ]) ?>

    <?= $form->field($model, 'capacity_available')->input('number', [
        'min' => 0,
        'step' => 1
    ]) ?>

    <?= $form->field($model, 'notes')->textarea([
        'rows' => 3,
        'placeholder' => 'Observações do alojamento'
    ]) ?>

    <?= $form->field($model, 'geometry')->textarea([
        'rows' => 6,
        'placeholder' => '{"type":"Point","coordinates":[300,200]}'
    ])->hint('GeoJSON da geometria do alojamento no mapa.') ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>