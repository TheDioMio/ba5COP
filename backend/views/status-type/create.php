<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\StatusType $model */

$this->title = 'Create Status Type';
$this->params['breadcrumbs'][] = ['label' => 'Status Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="status-type-create container-fluid">
    <div class="card card-outline card-success shadow-sm">
        <div class="card-header">
            <div class="card-tools">
                <?= Html::a('<i class="fas fa-arrow-left"></i>',
                    ['index'],
                    [
                        'class' => 'btn btn-outline-secondary mr-1',
                        'title' => 'Voltar',
                    ],
                )
                ?>
            </div>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
                'arrayEntityTypes' => $arrayEntityTypes,
            ]) ?>
        </div>
    </div>
</div>