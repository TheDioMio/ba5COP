<?php

namespace frontend\controllers;

use common\models\DecisionLog;
use common\models\Incident;
use common\models\IncidentType;
use common\models\Location;
use common\models\LocationType;
use common\models\LodgingEntry;
use common\models\LodgingSite;
use common\models\Request;
use common\models\RequestType;
use common\models\Task;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\Response;

class DashboardController extends Controller
{
    public function actionIndex()
    {
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
        $totalBeds = LodgingSite::getTotalBedsAll();
        $operationalBeds = LodgingSite::getTotalOperationalBeds();
        $occupiedBeds = LodgingSite::getTotalOccupiedBeds();
        $availableBeds = LodgingSite::getTotalAvailableBeds();
        $unavailableBeds = LodgingSite::getTotalUnavailableBeds();

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
        //pedidos externos que foram recusados
        $rejectedExternalRequests = Request::getExternalRejected();
        //pedidos externos em análise
        $inAnalisisExternalRequests = Request::getExternalInAnalisis();
        //pedidos externos aceites
        $acceptedExternalRequests = Request::getExternalAccepted();
        //pedidos novos nas ultimas 24h e que ainda estao no status "new"
        $newExternalRequests = Request::getExternalNew();

        //Data provider para o widget do KPI
        $externalRequestsProvider = new ActiveDataProvider([
            'query' => Request::findActiveExternal(),
            'pagination' => false,
            'sort' => false,
        ]);
        // --- FIM PEDIDOS EXTERNOS ---


        // --- TAREFAS CRÍTICAS ---
        //tarefas críticas (em todos os estados)
        $criticalTasks = Task::getCriticalTasks();
        //tarefas críticas que estejam ativas (new, doing)
        $activeCriticalTasks = Task::getActiveCriticalTasks();
        //tarefas críticas que estejam feitas
        $closedCriticalTasks = Task::getClosedCriticalTasks();
        //Data provider para o widget do KPI
        $criticalTasksProvider = new ActiveDataProvider([
            'query' => Task::findActiveCritical(),
            'pagination' => false,
            'sort' => false,
        ]);
        // --- FIM TAREFAS CRÍTICAS ---


        // --- EFETIVOS EXTERNOS ---
        //Efetivos externos, devolve um INT.
        $externalOccupancy = LodgingEntry::getExternalOccupancy();
        //Diferença de efetivos de hoje para ontem
        $yesterdayOccupancy = LodgingEntry::getExternalOccupancyAt(date('Y-m-d H:i:s', strtotime('-1 day')));
        $externalOccupancyDifference24H = $externalOccupancy - $yesterdayOccupancy;
        //Data provider para o widget do KPI
        $externalOccupancyProvider = new ActiveDataProvider([
            'query' => LodgingEntry::findActiveExternalOccupancy(),
            'pagination' => false,
            'sort' => false,
        ]);
        // --- FIM EFETIVOS EXTERNOS ---


        // --- MOBILIDADE ---
        $totalCriticalRoads = Location::getCriticalCorridors();
        $openCriticalRoads = Location::getCriticalCorridors('GREEN');
        $totalCriticalParkings = Location::getCriticalParkings();
        $openCriticalParkings = Location::getCriticalParkings('GREEN');
        //Data provider para o widget do KPI - ESTRADAS CRÍTICAS
        $criticalRoadsProvider = new ActiveDataProvider([
            'query' => Location::findCriticalRoads(),
            'pagination' => false,
            'sort' => false,
        ]);
        //Data provider para o widget do KPI - ESTACIONAMENTOS CRÍTICOS
        $criticalParkingsProvider = new ActiveDataProvider([
            'query' => Location::findCriticalParkings(),
            'pagination' => false,
            'sort' => false,
        ]);
        // --- FIM MOBILIDADE ---


        // --- VEDAÇÃO ---
        //% do perimetro operacional
        $perimeterPercentage = Location::getPerimeterOperationalPercentage();
        $inopPerimeterProvider = new ActiveDataProvider([
            'query' => Location::findPerimeterInop(),
            'pagination' => false,
            'sort' => false,
        ]);
        // --- FIM VEDAÇÃO ---


        // --- APOIOS PRESTADOS  ---
        //externo e interno
        $bathsGivenAccOverall = Request::getAllNumberRequestsOfType(RequestType::TYPE_BATH);
        $bathsGivenOntOverall = Request::getRequestsOfTypeWithin(RequestType::TYPE_BATH, 'yesterday');
        $bathsGivenHjOverall = Request::getRequestsOfTypeWithin(RequestType::TYPE_BATH, 'today');

        $mealsGivenAccOverall = Request::getAllNumberRequestsOfType(RequestType::TYPE_MEAL);
        $mealsGivenOntOverall = Request::getRequestsOfTypeWithin(RequestType::TYPE_MEAL, 'yesterday');
        $mealsGivenHjOverall = Request::getRequestsOfTypeWithin(RequestType::TYPE_MEAL, 'today');

        $bedsGivenAccOverall = Request::getAllNumberRequestsOfType(RequestType::TYPE_BED);
        $bedsGivenOntOverall = Request::getRequestsOfTypeWithin(RequestType::TYPE_BED, 'yesterday');
        $bedsGivenHjOverall = Request::getRequestsOfTypeWithin(RequestType::TYPE_BED, 'today');


        //interno
        $bathsGivenAccInternal = Request::getAllNumberRequestsOfType(RequestType::TYPE_BATH, 'internal');
        $bathsGivenOntInternal = Request::getRequestsOfTypeWithin(RequestType::TYPE_BATH, 'yesterday', 'internal');
        $bathsGivenHjInternal = Request::getRequestsOfTypeWithin(RequestType::TYPE_BATH, 'today', 'internal');

        $mealsGivenAccInternal = Request::getAllNumberRequestsOfType(RequestType::TYPE_MEAL, 'internal');
        $mealsGivenOntInternal = Request::getRequestsOfTypeWithin(RequestType::TYPE_MEAL, 'yesterday', 'internal');
        $mealsGivenHjInternal = Request::getRequestsOfTypeWithin(RequestType::TYPE_MEAL, 'today', 'internal');

        $bedsGivenAccInternal = Request::getAllNumberRequestsOfType(RequestType::TYPE_BED);
        $bedsGivenOntInternal = Request::getRequestsOfTypeWithin(RequestType::TYPE_BED, 'yesterday');
        $bedsGivenHjInternal = Request::getRequestsOfTypeWithin(RequestType::TYPE_BED, 'today');


        //externo
        $bathsGivenAccExternal = Request::getAllNumberRequestsOfType(RequestType::TYPE_BATH, 'external');
        $bathsGivenOntExternal = Request::getRequestsOfTypeWithin(RequestType::TYPE_BATH, 'yesterday', 'external');
        $bathsGivenHjExternal = Request::getRequestsOfTypeWithin(RequestType::TYPE_BATH, 'today', 'external');

        $mealsGivenAccExternal = Request::getAllNumberRequestsOfType(RequestType::TYPE_MEAL, 'external');
        $mealsGivenOntExternal = Request::getRequestsOfTypeWithin(RequestType::TYPE_MEAL, 'yesterday', 'external');
        $mealsGivenHjExternal = Request::getRequestsOfTypeWithin(RequestType::TYPE_MEAL, 'today', 'external');

        $bedsGivenAccExternal = Request::getAllNumberRequestsOfType(RequestType::TYPE_BED, 'external');
        $bedsGivenOntExternal = Request::getRequestsOfTypeWithin(RequestType::TYPE_BED, 'yesterday', 'external');
        $bedsGivenHjExternal = Request::getRequestsOfTypeWithin(RequestType::TYPE_BED, 'today', 'external');
        // --- FIM APOIOS PRESTADOS ---


        // --- SITUAÇÃO SANITÁRIA  ---

        // --- FIM SITUAÇÃO SANITÁRIA  ---


        // --- ESTADO DE SISTEMAS NAVids ---
        $navIDsArray = Location::getLocationsOfType(LocationType::TYPE_NAVAIDS);
        // --- FIM ESTADO DE SISTEMAS NAVids ---


        // --- TOP 10 TAREFAS  ---
        $latest10Tasks = Task::getLatest10Tasks();
        // --- FIM TOP 10 TAREFAS  ---


        // --- DECISIONS LOG ---
        $latest10Decisions = DecisionLog::getLatest10Decisions();
        // --- FIM DECISIONS LOG ---

        // --- RISCOS DO DIA ---
        $dailyRisks = Incident::getToBeCompletedToday();
        // --- FIM RISCOS DO DIA ---


        return $this->render('index', [
            'availableBeds' => $availableBeds,
            'totalBeds' => $totalBeds,
            'operationalBeds' => $operationalBeds,
            'unavailableBeds' => $unavailableBeds,
            'occupiedBeds' => $occupiedBeds,
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
            'activeWaterIncidents' => $activeWaterIncidents,
            'closedWaterIncidents' => $closedWaterIncidents,
            'waterIncidentsProvider' => $waterIncidentsProvider,
            'activeExternalRequests' => $activeExternalRequests,
            'closedExternalRequests' => $closedExternalRequests,
            'externalRequestsProvider' => $externalRequestsProvider,
            'activeCriticalTasks' => $activeCriticalTasks,
            'closedCriticalTasks' => $closedCriticalTasks,
            'criticalTasksProvider' => $criticalTasksProvider,
            'externalOccupancyDifference24H' => $externalOccupancyDifference24H,
            'externalOccupancyProvider' => $externalOccupancyProvider,
            'totalCriticalParkings' => $totalCriticalParkings,
            'openCriticalParkings' => $openCriticalParkings,
            'criticalRoadsProvider' => $criticalRoadsProvider,
            'criticalParkingsProvider' => $criticalParkingsProvider,
            'inopPerimeterProvider' => $inopPerimeterProvider,
            'rejectedExternalRequests' => $rejectedExternalRequests,
            'inAnalisisExternalRequests' => $inAnalisisExternalRequests,
            'acceptedExternalRequests' => $acceptedExternalRequests,
            'newExternalRequests' => $newExternalRequests,
            'bathsGivenAccOverall' => $bathsGivenAccOverall,
            'mealsGivenAccOverall' => $mealsGivenAccOverall,
            'mealsGivenOntOverall' => $mealsGivenOntOverall,
            'mealsGivenHjOverall' => $mealsGivenHjOverall,
            'bathsGivenOntOverall' => $bathsGivenOntOverall,
            'bathsGivenHjOverall' => $bathsGivenHjOverall,
            'bathsGivenAccInternal' => $bathsGivenAccInternal,
            'bathsGivenOntInternal' => $bathsGivenOntInternal,
            'bathsGivenHjInternal' => $bathsGivenHjInternal,
            'mealsGivenAccInternal' => $mealsGivenAccInternal,
            'mealsGivenOntInternal' => $mealsGivenOntInternal,
            'mealsGivenHjInternal' => $mealsGivenHjInternal,
            'bathsGivenAccExternal' => $bathsGivenAccExternal,
            'bathsGivenOntExternal' => $bathsGivenOntExternal,
            'bathsGivenHjExternal' => $bathsGivenHjExternal,
            'mealsGivenAccExternal' => $mealsGivenAccExternal,
            'mealsGivenOntExternal' => $mealsGivenOntExternal,
            'mealsGivenHjExternal' => $mealsGivenHjExternal,
            'bedsGivenAccOverall' => $bedsGivenAccOverall,
            'bedsGivenOntOverall' => $bedsGivenOntOverall,
            'bedsGivenHjOverall' => $bedsGivenHjOverall,
            'bedsGivenAccInternal' => $bedsGivenAccInternal,
            'bedsGivenOntInternal' => $bedsGivenOntInternal,
            'bedsGivenHjInternal' => $bedsGivenHjInternal,
            'bedsGivenAccExternal' => $bedsGivenAccExternal,
            'bedsGivenOntExternal' => $bedsGivenOntExternal,
            'bedsGivenHjExternal' => $bedsGivenHjExternal,
            'navIDsArray' => $navIDsArray,
            'latest10Tasks' => $latest10Tasks,
            'latest10Decisions' => $latest10Decisions,
            'dailyRisks' => $dailyRisks,
        ]);
    }


    /**
     * Esta action serve para ir à API de METEO buscar o REPORT 1H de meteo de Monte Real.
     * Ou seja, esta action é usada no JS, o JS faz o pedido a partir do PHP, e trata dos dados para a view HTML em JS.
     */
    public function actionMeteo()
    {
        //Avisar a action que é para devolver JSON, e não HTMl
        Yii::$app->response->format = Response::FORMAT_JSON;

        //endpoint da API com as informações de Monte Real, code LPMR. Atualiza de hora a hora.
        $url = 'https://aviationweather.gov/api/data/metar?ids=LPMR&format=json';

        //Vai buscar os dados à API
        $json = file_get_contents($url);

        //Se a API falhar, devolve erro.
        if ($json === false) {
            return [
                'success' => false,
                'message' => 'Erro ao obter dados da API',
            ];
        }

        //Se der para ir buscar, devolve um JSON com "true", a avisar que a resposta foi sucedida e a data.
        $data = json_decode($json, true);

        return [
            'success' => true,
            'data' => $data,
        ];
    }


    /**
     * Esta action serve para ir à API de METEO buscar o REPORT 24H de meteo de Monte Real.
     * Ou seja, esta action é usada no JS, o JS faz o pedido a partir do PHP, e trata dos dados para a view HTML em JS.
     */
    public function actionTaf()
    {
        //Avisar a action que é para devolver JSON, e não HTMl
        Yii::$app->response->format = Response::FORMAT_JSON;

        //endpoint da API com as informações de Monte Real, code LPMR. Atualiza de dia a dia.
        $url = 'https://aviationweather.gov/api/data/taf?ids=LPMR&format=json';

        //Vai buscar os dados à API
        $json = file_get_contents($url);

        //Se a API falhar, devolve erro.
        if ($json === false) {
            return [
                'success' => false,
                'message' => 'Erro ao obter dados da API',
            ];
        }

        //Se der para ir buscar, devolve um JSON com "true", a avisar que a resposta foi sucedida e a data.
        $data = json_decode($json, true);

        return [
            'success' => true,
            'data' => $data,
        ];
    }
}