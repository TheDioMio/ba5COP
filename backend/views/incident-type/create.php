<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\IncidentType $model */

$this->title = 'Create Incident Type';
$this->params['breadcrumbs'][] = ['label' => 'Incident Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="incident-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
