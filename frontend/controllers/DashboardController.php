<?php

namespace frontend\controllers;

use common\models\Incident;
use common\models\IncidentType;
use common\models\Location;
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
        $perimeterPercentage = Location::getPerimeterOperationalPercentage();
        $totalCriticalRoads = Location::getCriticalCorridors();
        $openCriticalRoads = Location::getCriticalCorridors('GREEN');

        return $this->render('index', [
            'overallAvailability' => $overallAvailability,
            'waterIncidents' => $waterIncidents,
            'securityIncidents' => $securityIncidents,
            'externalOccupancy' => $externalOccupancy,
            'externalRequests' => $externalRequests,
            'criticalTasks' => $criticalTasks,
            'perimeterPercentage' => $perimeterPercentage,
            'totalCriticalRoads' => $totalCriticalRoads,
            'openCriticalRoads' => $openCriticalRoads,
        ]);

    }
}