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
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class DashboardController extends Controller
{
    public function actionIndex(){
        $overallAvailability = LodgingSite::getOverallAvailability();
        $waterIncidents = Incident::incidentTotal(IncidentType::WATER_LEAK);

        $externalOccupancy = LodgingEntry::getExternalOccupancy();
        $externalRequests = Request::getExternalRequests();
        $criticalTasks = Task::getCriticalTasks();
        $perimeterPercentage = Location::getPerimeterOperationalPercentage();
        $totalCriticalRoads = Location::getCriticalCorridors();
        $openCriticalRoads = Location::getCriticalCorridors('GREEN');


        //--- INCIDENTES - SEGURANÇA ---

        //total de incidentes (geral)
        $securityIncidents = Incident::incidentTotal(IncidentType::SECURITY);

        //total de incidentes (que estão abertos)
        $activeSecurityIncidents = Incident::incidentActiveTotal(IncidentType::SECURITY);

        //total de incidentes (que estão fechados)
        $closedSecurityIncidents = Incident::incidentDoneTotal(IncidentType::SECURITY);

        $securityIncidentsProvider = new ActiveDataProvider([
            'query' => Incident::findActiveByType(IncidentType::SECURITY),
            'pagination' => false,
            'sort' => false,
        ]);
        //FIM INCIDENTES - SEGURANÇA

        return $this->render('index', [
            'overallAvailability' => $overallAvailability,
            'waterIncidents' => $waterIncidents,
            'securityIncidents' => $securityIncidents,
            'activeSecurityIncidents' => $activeSecurityIncidents,
            'externalOccupancy' => $externalOccupancy,
            'externalRequests' => $externalRequests,
            'criticalTasks' => $criticalTasks,
            'perimeterPercentage' => $perimeterPercentage,
            'totalCriticalRoads' => $totalCriticalRoads,
            'openCriticalRoads' => $openCriticalRoads,
            'closedSecurityIncidents' => $closedSecurityIncidents,
            'securityIncidentsProvider' => $securityIncidentsProvider,
        ]);

    }
}