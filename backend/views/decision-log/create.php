<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\DecisionLog $model */

$this->title = 'Create Decision Log';
$this->params['breadcrumbs'][] = ['label' => 'Decision Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="decision-log-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
