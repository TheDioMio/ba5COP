<?php

namespace backend\controllers;

use common\models\Entity;
use common\models\Priority;
use common\models\Request;
use app\models\RequestSearch;
use common\models\RequestType;
use common\models\StatusType;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RequestController implements the CRUD actions for Request model.
 */
class RequestController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
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
     * Lists all Request models.
     *
     * @return string
     */

    public function actionIndex()
    {
        $status = Yii::$app->request->get('status_type_id');

        $searchModelInternos = new RequestSearch();
        $searchModelInternos->fixedIsExternal = 0;
        $searchModelInternos->customFormName = 'RequestSearchInternos';

        $searchModelExternos = new RequestSearch();
        $searchModelExternos->fixedIsExternal = 1;
        $searchModelExternos->customFormName = 'RequestSearchExternos';

        $dataProviderInternos = $searchModelInternos->search(Yii::$app->request->queryParams);
        $dataProviderExternos = $searchModelExternos->search(Yii::$app->request->queryParams);

        if (!empty($status)) {
            $dataProviderInternos->query->andWhere(['status_type_id' => $status]);
            $dataProviderExternos->query->andWhere(['status_type_id' => $status]);
        }

        $statuses = StatusType::find()
            ->where(['entity_type_id' => Entity::REQUEST_ID])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        $priorityList = Priority::dropDown();

        return $this->render('index', [
            'searchModelInternos' => $searchModelInternos,
            'searchModelExternos' => $searchModelExternos,
            'dataProviderInternos' => $dataProviderInternos,
            'dataProviderExternos' => $dataProviderExternos,
            'priorityList' => $priorityList,
            'statuses' => $statuses,
            'status' => $status,
        ]);
    }

    /**
     * Displays a single Request model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Request model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate() {
        $model = new Request();

        $prioritiesArray = Priority::dropDown();
        $statusArray = StatusType::getStatusDropdown(Entity::REQUEST_ID);
        $requestTypeArray = RequestType::dropDown();

        if ($this->request->isPost) {
            $model->load($this->request->post());

            $entity = Entity::createEntity(Entity::REQUEST_ID);

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
            'prioritiesArray' => $prioritiesArray,
            'statusArray' => $statusArray,
            'requestTypeArray' => $requestTypeArray,
        ]);
    }

    /**
     * Updates an existing Request model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $prioritiesArray = Priority::dropDown();
        $requestTypeArray = RequestType::dropDown();
        $statusArray = StatusType::getStatusDropdown(Entity::REQUEST_ID);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'prioritiesArray' => $prioritiesArray,
            'requestTypeArray' => $requestTypeArray,
            'statusArray' => $statusArray,
        ]);
    }

    /**
     * Deletes an existing Request model.
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
     * Finds the Request model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Request the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Request::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
