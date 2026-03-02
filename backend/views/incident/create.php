<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Incident $model */

$this->title = 'Create Incident';
$this->params['breadcrumbs'][] = ['label' => 'Incidents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="incident-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
