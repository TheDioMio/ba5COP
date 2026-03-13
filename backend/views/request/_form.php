<?php

use common\models\Request;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Request $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="request-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="mb-3">
        <label class="form-label fw-semibold">Pedido Externo à BA5?</label>

        <?= $form->field($model, 'is_external')->radioList(
            [Request::NOT_EXTERNAL_REQUEST => 'Não', Request::EXTERNAL_REQUEST => 'Sim'],
            [
                'tag' => false,
                'class' => 'btn-group w-100',
                'item' => function ($index, $label, $name, $checked, $value) {

                    $id = 'external_'.$value;

                    if ($value == Request::EXTERNAL_REQUEST) {
                        $btnClass = $checked ? 'btn-success' : 'btn-outline-success';
                    } else {
                        $btnClass = $checked ? 'btn-danger' : 'btn-outline-danger';
                    }

                    return '
                    <input type="radio" class="btn-check" name="'.$name.'" id="'.$id.'" value="'.$value.'" '.($checked ? 'checked' : '').'>
                    <label class="btn '.$btnClass.' flex-fill" for="'.$id.'">'.$label.'</label>
                    ';
                }
            ]
        )->label(false) ?>
    </div>

    <?= $form->field($model, 'origin')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'details')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'priority_id')
        ->dropDownList($prioritiesArray, ['prompt' => '-- PRIORIDADE --'])
        ->label('Prioridade') ?>

    <?= $form->field($model, 'status')
        ->dropDownList($statusArray, ['prompt' => '-- STATUS --'])
        ->label('Status') ?>

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