<?php

namespace frontend\controllers;

use common\models\Incident;
use common\models\IncidentType;
use common\models\LodgingEntry;
use common\models\LodgingSite;
use common\models\Request;
use common\models\Task;
use Yii;
use yii\web\Controller;

class DashboardController extends Controller
{
    public function actionIndex(){
        $overallAvailability = LodgingSite::getOverallAvailability();
        $waterIncidents = Incident::incidentTotal(IncidentType::WATER_LEAK);
        $securityIncidents = Incident::incidentTotal(IncidentType::SECURITY);
        $externalOccupancy = LodgingEntry::getExternalOccupancy();
        $externalRequests = Request::getExternalRequests();
        $criticalTasks = Task::getCriticalTasks();

        return $this->render('index', [
            'overallAvailability' => $overallAvailability,
            'waterIncidents' => $waterIncidents,
            'securityIncidents' => $securityIncidents,
            'externalOccupancy' => $externalOccupancy,
            'externalRequests' => $externalRequests,
            'criticalTasks' => $criticalTasks,
        ]);

    }
}