<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Incident $model */

$this->title = 'Gestão de Incidentes';
$this->params['breadcrumbs'][] = ['label' => 'Incidents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="incident-create container-fluid">
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
                'arrayLocations' => $arrayLocations,
                'arrayIncidentTypes' => $arrayIncidentTypes,
                'arrayPriorities' => $arrayPriorities,
                'arrayStatus' => $arrayStatus,
                'arrayUsers' => $arrayUsers,
            ]) ?>
        </div>
    </div>
</div>