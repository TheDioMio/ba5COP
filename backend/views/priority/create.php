<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Priority $model */

$this->title = 'Create Priority';
$this->params['breadcrumbs'][] = ['label' => 'Priorities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="priority-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
