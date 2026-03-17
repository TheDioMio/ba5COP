<?php

namespace frontend\controllers;

use common\models\LodgingSite;
use Yii;
use yii\web\Controller;

class DashboardController extends Controller
{
    public function actionIndex(){
        $overallAvailability = LodgingSite::getOverallAvailability();


        return $this->render('index', [
            'overallAvailability' => $overallAvailability,
        ]);

    }
}