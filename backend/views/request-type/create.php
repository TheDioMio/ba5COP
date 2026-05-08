<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\RequestType $model */

$this->title = 'Gestão Tipo de Pedidos';
?>
<div class="request-type-create container-fluid">
    <div class="card card-outline card-success shadow-sm">
        <div class="card-header">
            <div class="card-tools">
                <?= Html::button('<i class="fas fa-arrow-left"></i>', [
                    'class' => 'btn btn-default',
                    'onclick' => 'history.back();',
                ]) ?>
            </div>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
