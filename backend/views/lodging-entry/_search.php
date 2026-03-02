<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\LodgingEntrySearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="lodging-entry-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'lodging_site_id') ?>

    <?= $form->field($model, 'branch_id') ?>

    <?= $form->field($model, 'people_count') ?>

    <?= $form->field($model, 'checkin_at') ?>

    <?php // echo $form->field($model, 'checkout_at') ?>

    <?php // echo $form->field($model, 'notes') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
