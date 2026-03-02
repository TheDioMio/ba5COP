<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\EntityUpdate $model */

$this->title = 'Create Entity Update';
$this->params['breadcrumbs'][] = ['label' => 'Entity Updates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entity-update-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
