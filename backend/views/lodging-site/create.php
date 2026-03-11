<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\LodgingSite $model */

$this->title = 'Gestão de Alojamentos';
$this->params['breadcrumbs'][] = ['label' => 'Lodging Sites', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lodging-site-create container-fluid">
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
                'arraySites' => $arraySites,
            ]) ?>
        </div>
    </div>
</div>