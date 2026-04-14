<?php

use common\models\LodgingEntry;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\LodgingEntrySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Lodging Entries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lodging-entry-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Lodging Entry', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'lodging_site_id',
            'branch_id',
            'people_count',
            'checkin_at',
            //'checkout_at',
            //'notes',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, LodgingEntry $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
