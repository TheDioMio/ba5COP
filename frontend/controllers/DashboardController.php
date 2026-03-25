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
use yii\data\ArrayDataProvider;
use yii\web\Controller;

class DashboardController extends Controller
{
    public function actionIndex(){
        $overallAvailability = LodgingSite::getOverallAvailability();

        $externalOccupancy = LodgingEntry::getExternalOccupancy();
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
        //Data provider para o widget do KPI (listagem dos incidentes ativos)
        $securityIncidentsProvider = new ActiveDataProvider([
            'query' => Incident::findActiveByType(IncidentType::SECURITY),
            'pagination' => false,
            'sort' => false,
        ]);
        //--- FIM INCIDENTES - SEGURANÇA ---


        //--- CAMAS ---
        //total de alojamentos que tenham camas disponíveis
        $availableLodgings = LodgingSite::findWithAvailableBeds();
        //total de camas ocupadas neste momento
        $occupiedBeds = LodgingEntry::getOverallOccupancy();
        //Data provider para o widget do KPI (listagem dos alojamentos com camas disponíveis)
        $availableLodgingsProvider = new ArrayDataProvider([
            'allModels' => $availableLodgings,
            'pagination' => false,
            'sort' => false,
        ]);
        //--- FIM CAMAS ---


        // --- INCIDENTES - ÁGUA ---
        //total de incidentes relacionados a àgua
        $waterIncidents = Incident::incidentTotal(IncidentType::WATER_LEAK);
        //total de incidentes (que estão abertos)
        $activeWaterIncidents = Incident::incidentActiveTotal(IncidentType::WATER_LEAK);
        //total de incidentes (que estão fechados)
        $closedWaterIncidents = Incident::incidentDoneTotal(IncidentType::WATER_LEAK);
        //Data provider para o widget do KPI (listagem dos incidentes relacionados com àgua)
        $waterIncidentsProvider = new ActiveDataProvider([
            'query' => Incident::findActiveByType(IncidentType::WATER_LEAK),
            'pagination' => false,
            'sort' => false,
        ]);
        // --- FIM INCIDENTES - ÁGUA ---


        // --- PEDIDOS EXTERNOS ---
        //pedidos externos (em todos os estados, done, rejected, in_progress... etc)
        $externalRequests = Request::getExternalRequests();
        //pedidos externos que estão ativos
        $activeExternalRequests = Request::getActiveExternal();
        //pedidos externos que já estão feitos, ou seja, foram aceites e depois fechados.
        $closedExternalRequests = Request::getExternalDone();
        //Data provider para o widget do KPI
        $externalRequestsProvider = new ActiveDataProvider([
            'query' => Request::findActiveExternal(),
            'pagination' => false,
            'sort' => false,
        ]);
        // --- FIM PEDIDOS EXTERNOS ---


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
            'availableLodgingsProvider' => $availableLodgingsProvider,
            'occupiedBeds' => $occupiedBeds,
            'activeWaterIncidents' => $activeWaterIncidents,
            'closedWaterIncidents' => $closedWaterIncidents,
            'waterIncidentsProvider' => $waterIncidentsProvider,
            'activeExternalRequests' => $activeExternalRequests,
            'closedExternalRequests' => $closedExternalRequests,
            'externalRequestsProvider' => $externalRequestsProvider,
        ]);

    }
}