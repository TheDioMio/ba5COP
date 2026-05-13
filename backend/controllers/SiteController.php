<?php

namespace backend\controllers;

use common\models\Location;
use common\models\LocationType;
use common\models\LodgingSite;
use common\models\LoginForm;
use common\models\Request;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function () {
                    if (Yii::$app->user->can('login.backend')) {
                        return Yii::$app->response->redirect(['/site/index']);
                    }

                    return Yii::$app->response->redirect(['/site/login']);
                },
                'except' => ['error'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'error'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['clean-database'],
                        'roles' => ['sensibleEntity.manage'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['login.backend'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                    'clean-database' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    public function beforeAction($action) {
        //Isto executa a lógica padrão do Controller
        if (!parent::beforeAction($action)) {
            return false;
        }

        //Lógica para passar o utilizador para o Layout
        //Usamos Yii::$app->user->identity que já é seguro (retorna null se não houver login)
        $userLogado = Yii::$app->user->identity;

        //Define uma variável global do Layout, neste caso com o utilizador logado (View Params)
        //Como é impossível um guest aceder à Dashboard, não há necessidade de tratar do null que isto pode devolver.
        $this->view->params['userLogado'] = $userLogado;



        return true;
    }
    public function actionIndex() {
        $locationTypes = LocationType::dropDown();
        $locationsCount = count(Location::dropDown());
        $lodgingSitesCount = count(LodgingSite::dropDown());
//        $pendingRequestsCount = Request::get

        return $this->render(
            'index', [
            'locationTypes' => $locationTypes,
            'locationsCount' => $locationsCount,
            'lodgingSitesCount' => $lodgingSitesCount,
        ]);
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            // Verifica permissão para acessar o backend
            if (Yii::$app->user->can('login.backend')) {
                return $this->goHome();
            } else {
                Yii::$app->user->logout();
                Yii::$app->session->setFlash('error', 'You are not allowed to access the backend.');
                return $this->redirect(['site/login']);
            }
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // Depois do login, verificar permissão
            if (Yii::$app->user->can('login.backend')) {
                return $this->goBack();
            } else {
                Yii::$app->user->logout();
                Yii::$app->session->setFlash('error', 'You are not allowed to access the backend.');
                return $this->redirect(['site/login']);
            }
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;

        // Se NÃO estiver logado → redireciona para LOGIN
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login']);
        }

        return $this->render('error', [
            'exception' => $exception,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionErrorPage($type = 'generic')
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login']);
        }

        $allowedTypes = [
            'action-unavailable',
            'access-denied',
            'not-found',
            'maintenance',
        ];

        if (!in_array($type, $allowedTypes)) {
            $type = 'action-unavailable';
        }

        return $this->render("errors/{$type}");
    }

    public function actionCleanDatabase() {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();

        try {
            /*
             * Mantém tabelas auxiliares:
             * - location_type
             * - status_type
             * - priority
             * - entity_type
             * - incident_type
             * - request_type
             * Mantém também users/RBAC.
             */

            $tablesToClean = [
                'audit_log',
                'entity_update',
                'decision_log',
                'task',
                'request',
                'incident',
                'lodging_site',
                'lodging_entry',
                'location',
                'entity',
            ];

            $db->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();

            foreach ($tablesToClean as $table) {
                $db->createCommand()->delete($table)->execute();
                $db->createCommand("ALTER TABLE `$table` AUTO_INCREMENT = 1")->execute();
            }

            $db->createCommand('SET FOREIGN_KEY_CHECKS=1')->execute();

            $transaction->commit();

            return [
                'ok' => true,
                'message' => 'Base de dados limpa com sucesso.',
            ];

        } catch (\Throwable $e) {
            $db->createCommand('SET FOREIGN_KEY_CHECKS=1')->execute();
            $transaction->rollBack();

            throw $e;
        }
    }
}
