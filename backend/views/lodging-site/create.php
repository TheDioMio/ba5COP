<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\LodgingSite $model */

$this->title = 'Create Lodging Site';
$this->params['breadcrumbs'][] = ['label' => 'Lodging Sites', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lodging-site-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
