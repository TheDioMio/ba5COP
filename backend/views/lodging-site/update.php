<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\LodgingSite $model */

$this->title = 'Update Lodging Site: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Lodging Sites', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lodging-site-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
