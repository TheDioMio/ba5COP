<?php

namespace frontend\controllers;

use common\models\Incident;
use common\models\IncidentType;
use common\models\LodgingSite;
use Yii;
use yii\web\Controller;

class DashboardController extends Controller
{
    public function actionIndex(){
        $overallAvailability = LodgingSite::getOverallAvailability();
        $waterIncidents = Incident::incidentTotal(IncidentType::WATER_LEAK);
        $securityIncidents = Incident::incidentTotal(IncidentType::SECURITY);



        return $this->render('index', [
            'overallAvailability' => $overallAvailability,
            'waterIncidents' => $waterIncidents,
            'securityIncidents' => $securityIncidents,
        ]);

    }
}