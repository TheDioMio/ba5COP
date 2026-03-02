<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\LodgingEntry $model */

$this->title = 'Create Lodging Entry';
$this->params['breadcrumbs'][] = ['label' => 'Lodging Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lodging-entry-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
