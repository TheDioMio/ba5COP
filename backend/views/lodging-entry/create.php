<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\LodgingEntry $model */

$this->title = 'Catalogar Entrada';
$this->params['breadcrumbs'][] = ['label' => 'Lodging Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lodging-entry-create container-fluid">
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
                'unitArray' => $unitArray,
            ]) ?>
        </div>
    </div>
</div>