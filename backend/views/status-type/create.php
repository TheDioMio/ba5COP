<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\StatusType $model */

$this->title = 'Create Status Type';
$this->params['breadcrumbs'][] = ['label' => 'Status Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="status-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
