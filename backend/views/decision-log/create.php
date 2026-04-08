<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\DecisionLog $model */

$this->title = 'Gestão do Log de Decisões';
$this->params['breadcrumbs'][] = ['label' => 'Decision Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="decision-log-create container-fluid">
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
                'usersArray' => $usersArray,
                'statusArray' => $statusArray,
            ]) ?>
        </div>
    </div>
</div>
