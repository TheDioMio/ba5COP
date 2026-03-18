<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\LodgingEntry $model */

$this->title = 'Update Lodging Entry: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lodging Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lodging-entry-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'unitArray' => $unitArray,
    ]) ?>

</div>
