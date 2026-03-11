<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Request $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="request-form">

    <?php $form = ActiveForm::begin(); ?>

<!--    --><?php //= $form->field($model, 'is_external')->radioList(
//        [0 => 'Não', 1 => 'Sim'],
//        [
//            'class' => 'btn-group',
//            'data-toggle' => 'buttons',
//            'item' => function ($index, $label, $name, $checked, $value) {
//
//                $checkedClass = $checked ? 'active' : '';
//
//                return '
//                <input type="radio" class="btn-check" name="'.$name.'" id="external'.$value.'" value="'.$value.'" '.($checked ? 'checked' : '').'>
//                <label class="btn btn-outline-primary '.$checkedClass.'" for="external'.$value.'">'.$label.'</label>
//            ';
//            }
//        ]
//    )->label('Externo') ?>

    <?= $form->field($model, 'origin')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'details')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'priority_id')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'entity_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<script>
    const toggle = document.getElementById('external-toggle');
    const no = document.getElementById('label-no');
    const yes = document.getElementById('label-yes');

    function updateLabels() {
        if (toggle.checked) {
            yes.classList.add('text-success','fw-semibold');
            yes.classList.remove('text-muted');

            no.classList.remove('text-success','fw-semibold');
            no.classList.add('text-muted');
        } else {
            no.classList.add('text-success','fw-semibold');
            no.classList.remove('text-muted');

            yes.classList.remove('text-success','fw-semibold');
            yes.classList.add('text-muted');
        }
    }

    toggle.addEventListener('change', updateLabels);
    updateLabels();
</script>