<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->textInput() ?>
    <?= $form->field($model, 'username')->textInput() ?>

    <?= $form->field($model, 'password')
        ->passwordInput()?>

    <?= $form->field($model, 'role_name')
        ->dropDownList(
            ArrayHelper::map($roles, 'name', 'description'),
            ['prompt' => 'Selecione o role']
        ) ?>

    <?= $form->field($model, 'status')->dropDownList([10 => 'Ativo', 9 => 'Inativo']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
