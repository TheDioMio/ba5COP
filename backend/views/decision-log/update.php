<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\DecisionLog $model */

$this->title = 'Update Decision Log: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Decision Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="decision-log-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
