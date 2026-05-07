<?php
use yii\helpers\Html;

$this->title = 'Gestão de Utilizadores';
?>

<div class="user-update container-fluid">
    <div class="card card-outline card-success shadow-sm">
        <div class="card-header">
            <div class="card-tools">
                <?= Html::button('<i class="fas fa-arrow-left"></i>', [
                    'class' => 'btn btn-default',
                    'onclick' => 'history.back();',
                ]) ?>
            </div>
        </div>
        <div class="card-body">
            <?= $this->render('_form', [
                'model' => $model,
                'roles' => $roles,
            ]) ?>
        </div>
    </div>
</div>
