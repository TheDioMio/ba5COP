<?php

namespace backend\controllers;

use common\models\Entity;
use common\models\Incident;
use common\models\Location;
use common\models\Priority;
use common\models\StatusType;
use common\models\Task;
use app\models\TaskSearch;
use common\models\User;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
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
     * Lists all Task models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Task model.
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
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($incident_id = null) {
        $model = new Task();
        $locationsArray = Location::dropDown();
        $incidentsArray = Incident::dropDown();
        $prioritiesArray = Priority::dropDown();
        $statusArray = StatusType::getStatusDropdown(Entity::TASK_ID);
        $usersArray = User::dropDown();

        if ($this->request->isPost) {
            $model->load($this->request->post());

            if ($incident_id !== null) {
                $incident = Incident::findOne($incident_id);

                if ($incident !== null) {
                    $model->incident_id = $incident->id;
                    $model->location_id = $incident->location_id;
                }
            }

            $entity = Entity::createEntity(Entity::TASK_ID);

            if ($entity !== null) {
                $model->entity_id = $entity->id;
                $model->status_type_id = StatusType::STATUS_TASK_NEW;
                $model->created_by = Yii::$app->user->id;
                $model->created_at = date('Y-m-d H:i:s');

                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();

            if ($incident_id !== null) {
                $incident = Incident::findOne($incident_id);

                if ($incident !== null) {
                    $model->incident_id = $incident->id;
                    $model->location_id = $incident->location_id;
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'locationsArray' => $locationsArray,
            'incidentsArray' => $incidentsArray,
            'prioritiesArray' => $prioritiesArray,
            'statusArray' => $statusArray,
            'usersArray' => $usersArray,
        ]);
    }

    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $locationsArray = Location::dropDown();
        $incidentsArray = Incident::dropDown();
        $prioritiesArray = Priority::dropDown();
        $statusArray = StatusType::getStatusDropdown(Entity::TASK_ID);
        $usersArray = User::dropDown();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'locationsArray' => $locationsArray,
            'incidentsArray' => $incidentsArray,
            'prioritiesArray' => $prioritiesArray,
            'statusArray' => $statusArray,
            'usersArray' => $usersArray,
        ]);
    }

    /**
     * Deletes an existing Task model.
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
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionChangeStatus($id) {
        $model = $this->findModel($id);

        switch ($model->status_type_id) {
            case StatusType::STATUS_TASK_NEW:
                $model->status_type_id = StatusType::STATUS_TASK_DOING;
                break;

            case StatusType::STATUS_TASK_DOING:
                $model->status_type_id = StatusType::STATUS_TASK_DONE;
                break;

            default:
                return $this->redirect(Yii::$app->request->referrer ?: ['incident/index']);
        }

        $model->save(false);

        return $this->redirect(Yii::$app->request->referrer ?: ['incident/index']);
    }
}
