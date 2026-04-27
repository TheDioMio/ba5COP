<?php

namespace backend\controllers;

use common\models\DecisionLog;
use app\models\DecisionLogSearch;
use common\models\Entity;
use common\models\StatusType;
use common\models\User;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DecisionLogController implements the CRUD actions for DecisionLog model.
 */
class DecisionLogController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
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
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all DecisionLog models.
     *
     * @return string
     */
    public function actionIndex() {
        $searchModel = new DecisionLogSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DecisionLog model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DecisionLog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate() {
        $model = new DecisionLog();
        $usersArray = User::dropDown();
        $statusArray = StatusType::getStatusDropdown(Entity::DECISION_ID);

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $entity = Entity::createEntity(Entity::DECISION_ID);

            if ($entity !== null) {
                $model->entity_id = $entity->id;

                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }
        return $this->render('create', [
            'model' => $model,
            'usersArray' => $usersArray,
            'statusArray' => $statusArray,
        ]);
    }

    /**
     * Updates an existing DecisionLog model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $usersArray = User::dropDown();
        $statusArray = StatusType::getStatusDropdown(Entity::DECISION_ID);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'usersArray' => $usersArray,
            'statusArray' => $statusArray,
        ]);
    }

    /**
     * Deletes an existing DecisionLog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DecisionLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return DecisionLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DecisionLog::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
