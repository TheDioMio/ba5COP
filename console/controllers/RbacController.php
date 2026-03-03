<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    /**
     * Executar:
     * php yii rbac/init
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // -------------------- PERMISSIONS --------------------
        // Login Frontend/Backend
        $loginFrontend = $this->addPermission($auth, 'login.frontend', 'Acesso ao Frontend');
        $loginBackend = $this->addPermission($auth, 'login.backend', 'Acesso ao Backend');

        // Sistema
        $userManage = $this->addPermission($auth, 'user.manage', 'CRUD de utilizadores');
        $auditView  = $this->addPermission($auth, 'audit.view', 'Ver auditoria');

        // COP / Mapa
        $copView  = $this->addPermission($auth, 'cop.view', 'Ver dashboard COP');
        $mapView  = $this->addPermission($auth, 'map.view', 'Ver mapa');
        $mapManage = $this->addPermission($auth, 'map.manage', 'Gerir mapa (camadas, editar, exportar, etc.)');

        // Incidentes
        $incidentManage = $this->addPermission($auth, 'incident.manage', 'Criar/editar/atribuir incidentes');
        $incidentClose  = $this->addPermission($auth, 'incident.close', 'Fechar/reabrir incidentes');

        // Tasks
        $taskManage   = $this->addPermission($auth, 'task.manage', 'Criar/editar/atribuir tasks');
        $taskComplete = $this->addPermission($auth, 'task.complete', 'Concluir/reabrir tasks');

        // Requests
        $requestManage = $this->addPermission($auth, 'request.manage', 'Criar/editar/atribuir/fechar pedidos');
        $requestDecide = $this->addPermission($auth, 'request.decide', 'Aprovar/recusar pedidos');

        // (Opcional) se fores mesmo usar
        $kpiManage      = $this->addPermission($auth, 'kpi.manage', 'Gerir KPIs (ver/editar/publicar)');
        $decisionManage = $this->addPermission($auth, 'decision.manage', 'Gerir decisões (criar/editar/publicar)');


        // -------------------- ROLES --------------------

        // admin: acesso total
        $admin = $auth->createRole('admin');
        $admin->description = 'Administrador';
        $auth->add($admin);

        // comandante: chefia/decisão (sem módulos de gestão do backend)
        $comandante = $auth->createRole('comandante');
        $comandante->description = 'Comandante da Base Aérea N.º5';
        $auth->add($comandante);

        // operador: operação diária
        $operador = $auth->createRole('Operador');
        $operador->description = 'Operador';
        $auth->add($operador);

        // gestorPedidos: triagem de pedidos
        $gestorPedidos = $auth->createRole('gestorPedidos');
        $gestorPedidos->description = 'Gestor de Pedidos';
        $auth->add($gestorPedidos);


        // -------------------- ROLE -> PERMISSIONS --------------------

        // ADMINISTRADOR
        $auth->addChild($admin, $loginFrontend);
        $auth->addChild($admin, $loginBackend);
        $auth->addChild($admin, $userManage);
        $auth->addChild($admin, $auditView);
        $auth->addChild($admin, $copView);
        $auth->addChild($admin, $mapView);
        $auth->addChild($admin, $mapManage);
        $auth->addChild($admin, $incidentManage);
        $auth->addChild($admin, $incidentClose);
        $auth->addChild($admin, $taskManage);
        $auth->addChild($admin, $taskComplete);
        $auth->addChild($admin, $requestManage);
        $auth->addChild($admin, $requestDecide);
        // ADMINISTRADOR


        // COMANDANTE
        $auth->addChild($comandante, $loginFrontend);
        $auth->addChild($comandante, $copView);
        $auth->addChild($comandante, $mapView);
        $auth->addChild($comandante, $incidentManage);
        $auth->addChild($comandante, $incidentClose);
        $auth->addChild($comandante, $taskManage);
        $auth->addChild($comandante, $taskComplete);
        $auth->addChild($comandante, $requestManage);
        $auth->addChild($comandante, $requestDecide);
        $auth->addChild($comandante, $auditView);
        // COMANDANTE

        // OPERADOR
        $auth->addChild($operador, $loginFrontend);
        $auth->addChild($operador, $copView);
        $auth->addChild($operador, $mapView);
        $auth->addChild($operador, $incidentManage);
        $auth->addChild($operador, $taskManage);
        $auth->addChild($operador, $taskComplete);
        // OPERADOR

        // GESTOR DE PEDIDOS
        $auth->addChild($gestorPedidos, $loginFrontend);
        $auth->addChild($gestorPedidos, $copView);
        $auth->addChild($gestorPedidos, $mapView);
        $auth->addChild($gestorPedidos, $requestManage);
        // GESTOR DE PEDIDOS

        // -------------------- ASSIGNMENTS --------------------
        // Dá admin ao user id=1
        $auth->assign($admin, 1);
    }

    private function addPermission($auth, string $name, string $description)
    {
        $p = $auth->createPermission($name);
        $p->description = $description;
        $auth->add($p);
        return $p;
    }
}