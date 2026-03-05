<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\AuditLog $model */

$this->title = 'Criar Registo de Edição';
$this->params['breadcrumbs'][] = ['label' => 'Audit Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="audit-log-create container-fluid">
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
                'usersArray' => $usersArray,
                'entitiesArray' => $entitiesArray,
            ]) ?>
        </div>
    </div>
</div>
