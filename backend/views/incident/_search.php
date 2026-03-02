<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\IncidentSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="incident-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'location_id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'incident_type_id') ?>

    <?php // echo $form->field($model, 'priority_id') ?>

    <?php // echo $form->field($model, 'status_type_id') ?>

    <?php // echo $form->field($model, 'reported_by') ?>

    <?php // echo $form->field($model, 'entity_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
