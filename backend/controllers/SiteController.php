<?php

namespace backend\controllers;

use common\models\LocationType;
use common\models\LoginForm;
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
                    //se tiver acesso ao Backend redireciona para a home do back se não, redireciona para para o login
                    if (Yii::$app->user->can('login.backend')) {
                        return Yii::$app->response->redirect(['/site/index']);
                    }
                    return Yii::$app->response->redirect(['/site/login']);

                },
                'except' => ['error'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
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

        return $this->render(
            'index', [
            'locationTypes' => $locationTypes,
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
}
