<?php

namespace backend\controllers;

use common\models\Entity;
use common\models\Priority;
use common\models\Request;
use app\models\RequestSearch;
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
        $status = Yii::$app->request->get('status');
        $priorityInternal = Yii::$app->request->get('priority_internal');
        $priorityExternal = Yii::$app->request->get('priority_external');

        $queryInternos = Request::find()->where(['is_external' => 0]);
        $queryExternos = Request::find()->where(['is_external' => 1]);

        // filtro global por status (REQUEST = entity_type_id 4)
        if (!empty($status)) {
            $queryInternos->andWhere(['status' => $status]);
            $queryExternos->andWhere(['status' => $status]);
        }

        // filtro de prioridade só para internos
        if (!empty($priorityInternal)) {
            $queryInternos->andWhere(['priority_id' => $priorityInternal]);
        }

        // filtro de prioridade só para externos
        if (!empty($priorityExternal)) {
            $queryExternos->andWhere(['priority_id' => $priorityExternal]);
        }

        $dataProviderInternos = new ActiveDataProvider([
            'query' => $queryInternos,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $dataProviderExternos = new ActiveDataProvider([
            'query' => $queryExternos,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $statuses = StatusType::find()
            ->where(['entity_type_id' => 4])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        $priorities = Priority::find()
            ->orderBy(['id' => SORT_ASC])
            ->all();

        return $this->render('index', [
            'dataProviderInternos' => $dataProviderInternos,
            'dataProviderExternos' => $dataProviderExternos,
            'statuses' => $statuses,
            'priorities' => $priorities,
            'status' => $status,
            'priorityInternal' => $priorityInternal,
            'priorityExternal' => $priorityExternal,
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
    public function actionCreate()
    {
        $model = new Request();

        $statusType = new StatusType();
        $prioritiesArray = Priority::dropDown();
        $statusArray = $statusType->getStatusDropdown(Entity::REQUEST_ID);

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

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'prioritiesArray' => $prioritiesArray,
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
