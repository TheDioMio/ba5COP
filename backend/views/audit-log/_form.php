<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\AuditLog $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="audit-log-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->dropDownList(
            $usersArray,
            ['prompt' => 'Selecione o utilizador...']
    )->label('Utilizador') ?>

    <?= $form->field($model, 'entity_id')->dropDownList(
        $entitiesArray,
        ['prompt' => 'Selecione a untidade...']
    )->label('Entidade') ?>

    <?= $form->field($model, 'action')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
