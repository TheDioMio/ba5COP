<?php

namespace backend\controllers;

use common\models\Entity;
use common\models\Location;
use common\models\LocationType;
use common\models\LodgingSite;
use app\models\LocationSearch;
use Yii;
use yii\db\Expression;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * LocationController implements the CRUD actions for Location model.
 */
class LocationController extends Controller
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
                    'class' => VerbFilter::class,
                    'actions' => [
                        'map-index' => ['GET'],
                        'map-create' => ['POST'],
                        'map-update' => ['POST'],
                        'map-delete' => ['POST'],
                    ],
                ],
            ]
        );
    }


    public function beforeAction($action) {
        if (in_array($action->id, ['create', 'update'])) {
            // bloqueia acesso direto
            return $this->redirect(['site/error-page', 'type' => 'action-unavailable']);
        }

        return parent::beforeAction($action);
    }


    /**
     * Lists all Location models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new LocationSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Location model.
     *
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
     * Creates a new Location model.
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate() {
        $model = new Location();
        $locationTypeArray = LocationType::dropDown();

        if ($this->request->isPost) {
           $model->load($this->request->post());

           $entity = Entity::createEntity(Entity::LOCATION_ID);
           if($entity !== null) {
               $model->entity_id = $entity->id;

               if($model->save()) {
                   return $this->redirect(['view', 'id' => $model->id]);
               }
           }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'locationTypeArray' => $locationTypeArray,
        ]);
    }

    /**
     * Updates an existing Location model.
     *
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {

        $model = $this->findModel($id);
        $locationTypeArray = LocationType::dropDown();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'locationTypeArray' => $locationTypeArray,
        ]);
    }

    /**
     * Deletes an existing Location model.
     *
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
     * Finds the Location model based on its primary key value.
     *
     * @param int $id ID
     * @return Location the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Location::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Devolve uma FeatureCollection única com:
     * - locations
     * - lodging sites
     *
     * Isto permite ao mapa mostrar ambas as entidades ao mesmo tempo.
     */
    public function actionMapIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $features = [];

        // =========================
        // LOCATIONS
        // =========================
        $locations = Location::find()
            ->with(['locationType', 'statusType'])
            ->where(['not', ['geometry' => null]])
            ->andWhere(['<>', 'geometry', ''])
            ->all();

        foreach ($locations as $location) {
            $feature = $location->toGeoJsonFeature();

            if ($feature !== null) {
                $features[] = $feature;
            }
        }

        // =========================
        // LODGING SITES
        // =========================
        $lodgingSites = LodgingSite::find()
            ->where(['not', ['geometry' => null]])
            ->andWhere(['<>', 'geometry', ''])
            ->all();

        foreach ($lodgingSites as $lodgingSite) {
            $feature = $lodgingSite->toGeoJsonFeature();

            if ($feature !== null) {
                $features[] = $feature;
            }
        }

        return [
            'type' => 'FeatureCollection',
            'features' => $features,
        ];
    }

    /**
     * Cria uma nova location via mapa.
     */
    public function actionMapCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $data = json_decode(Yii::$app->request->getRawBody(), true);

        if (!$data) {
            throw new \yii\web\BadRequestHttpException('JSON inválido.');
        }

        $name = trim((string)($data['name'] ?? 'Novo local'));
        $notes = trim((string)($data['notes'] ?? '')) ?: null;
        $locationTypeId = (int)($data['location_type_id'] ?? 3);
        $statusTypeId = (int)($data['status_type_id'] ?? 1);
        $geometry = $data['geometry'] ?? null;
        $isCritical = (int)($data['is_critical'] ?? 0);

        if (!$geometry || !is_array($geometry)) {
            throw new \yii\web\BadRequestHttpException('geometry em falta.');
        }

        $geomJson = json_encode($geometry, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        // Cria registo entity associado à location com ID 1XXXX
        $entity = Entity::createEntity(Entity::LOCATION_ID);

        if ($entity === null) {
            throw new \yii\web\ServerErrorHttpException('Erro ao criar entity.');
        }

        $model = new Location();
        $model->location_type_id = $locationTypeId;
        $model->name = $name;
        $model->notes = $notes;
        $model->geometry = $geomJson;
        $model->status_type_id = $statusTypeId;
        $model->is_critical = $isCritical;
        $model->entity_id = $entity->id;

        if (!$model->save()) {
            throw new \yii\web\ServerErrorHttpException(json_encode($model->errors));
        }

        return [
            'ok' => true,
            'id' => $model->id,
        ];
    }

    /**
     * Atualiza atributos e/ou geometria de uma location via mapa.
     */
    public function actionMapUpdate($id) {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $data = json_decode(Yii::$app->request->getRawBody(), true);

        if (!$data) {
            throw new \yii\web\BadRequestHttpException('JSON inválido.');
        }

        $model = $this->findModel($id);

        if (array_key_exists('name', $data)) {
            $model->name = trim((string)$data['name']) ?: 'Novo local';
        }

        if (array_key_exists('notes', $data)) {
            $model->notes = trim((string)$data['notes']) ?: null;
        }

        if (array_key_exists('location_type_id', $data)) {
            $model->location_type_id = (int)$data['location_type_id'];
        }

        if (array_key_exists('status_type_id', $data)) {
            $model->status_type_id = (int)$data['status_type_id'];
        }

        if (array_key_exists('is_critical', $data)) {
            $model->is_critical = (int)$data['is_critical'];
        }

        if (array_key_exists('geometry', $data) && is_array($data['geometry'])) {
            $model->geometry = json_encode(
                $data['geometry'],
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            );
        }

        if (!$model->save()) {
            throw new \yii\web\ServerErrorHttpException(
                json_encode($model->errors, JSON_UNESCAPED_UNICODE)
            );
        }

        return ['ok' => true];
    }

    /**
     * Apaga uma location via mapa.
     */
    public function actionMapDelete($id) {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = $this->findModel($id);

        if ($model->delete() === false) {
            throw new \yii\web\ServerErrorHttpException('Erro ao apagar location.');
        }

        return ['ok' => true];
    }
}