<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\AuditLog $model */

$this->title = 'Update Audit Log: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Audit Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="audit-log-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'usersArray' => $usersArray,
    ]) ?>

</div>
