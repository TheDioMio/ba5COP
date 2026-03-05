<?php

namespace backend\controllers;

use common\models\Location;
use app\models\LocationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use yii\db\Query;

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
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'map-index' => ['GET'],
                        'map-create' => ['POST'],
                        'map-update' => ['POST'],   //POST EM VEZ DE PATCH
                        'map-delete' => ['POST'],
                    ],
                ],
            ]
        );
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
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Location();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Location model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Location model.
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
     * Finds the Location model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
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

    public function actionMapIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $rows = (new Query())
            ->from('location')
            ->select(['id','name','location_type_id','status_type_id','geometry'])
            ->all();

        $features = [];

        foreach ($rows as $r) {
            $raw = $r['geometry'];

            // tenta JSON normal
            $geom = json_decode($raw, true);

            // fallback: JSON escapado tipo {\"type\":...}
            if (!$geom && is_string($raw)) {
                $geom = json_decode(stripslashes($raw), true);
            }

            if (!$geom || !isset($geom['type'], $geom['coordinates'])) {
                continue;
            }

            $features[] = [
                'type' => 'Feature',
                'id' => (int)$r['id'],
                'properties' => [
                    'name' => $r['name'],
                    'location_type_id' => (int)$r['location_type_id'],
                    'status_type_id' => (int)$r['status_type_id'],
                ],
                'geometry' => $geom,
            ];
        }

        return ['type' => 'FeatureCollection', 'features' => $features];
    }

    public function actionMapCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $data = json_decode(Yii::$app->request->getRawBody(), true);
        if (!$data) throw new BadRequestHttpException('JSON inválido.');

        $name = trim((string)($data['name'] ?? 'Novo local'));
        $locationTypeId = (int)($data['location_type_id'] ?? 3); // POINT
        $statusTypeId = (int)($data['status_type_id'] ?? 1);     // GREEN
        $geometry = $data['geometry'] ?? null;

        if (!$geometry || !is_array($geometry)) {
            throw new BadRequestHttpException('geometry em falta.');
        }

        // guarda JSON limpo (sem escapes)
        $geomJson = json_encode($geometry, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);

        // entity_id: no modelo, location tem entity_id NOT NULL :contentReference[oaicite:3]{index=3}
        // Para já, cria entity primeiro (entity_type_id=1 para LOCATION) :contentReference[oaicite:4]{index=4}
        Yii::$app->db->createCommand()->insert('entity', ['entity_type_id' => 1])->execute();
        $entityId = (int)Yii::$app->db->getLastInsertID();

        Yii::$app->db->createCommand()->insert('location', [
            'location_type_id' => $locationTypeId,
            'name' => $name,
            'geometry' => $geomJson,
            'status_type_id' => $statusTypeId,
            'entity_id' => $entityId,
            'updated_at' => new \yii\db\Expression('CURRENT_TIMESTAMP'),
        ])->execute();

        $id = (int)Yii::$app->db->getLastInsertID();

        return ['ok' => true, 'id' => $id];
    }

    public function actionMapUpdate($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $data = json_decode(Yii::$app->request->getRawBody(), true);
        if (!$data) throw new BadRequestHttpException('JSON inválido.');

        $geometry = $data['geometry'] ?? null;
        if (!$geometry || !is_array($geometry)) {
            throw new BadRequestHttpException('geometry em falta.');
        }

        $geomJson = json_encode($geometry, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);

        Yii::$app->db->createCommand()->update('location', [
            'geometry' => $geomJson,
            'updated_at' => new \yii\db\Expression('CURRENT_TIMESTAMP'),
        ], ['id' => (int)$id])->execute();

        return ['ok' => true];
    }

    public function actionMapDelete($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        // Como não tem is_deleted em location, apaga mesmo. POSSIVELMENTE ADICIONAR PARA SOFT-DELETE?
        Yii::$app->db->createCommand()->delete('location', ['id' => (int)$id])->execute();

        return ['ok' => true];
    }



}
