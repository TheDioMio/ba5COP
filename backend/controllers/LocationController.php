<?php

namespace backend\controllers;

use common\models\Location;
use app\models\LocationSearch;
use common\models\LocationType;
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
        $locationTypeArray = LocationType::dropDown();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
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
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
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

        $rows = (new \yii\db\Query())
            ->from('location')
            ->select([
                'id',
                'name',
                'notes',
                'location_type_id',
                'status_type_id',
                'is_critical',
                'geometry'
            ])
            ->all();

        $features = [];

        foreach ($rows as $r) {
            $geom = json_decode($r['geometry'], true);

            if (!$geom && is_string($r['geometry'])) {
                $geom = json_decode(stripslashes($r['geometry']), true);
            }

            if (!$geom || !isset($geom['type'], $geom['coordinates'])) {
                continue;
            }

            $features[] = [
                'type' => 'Feature',
                'id' => (int)$r['id'],
                'properties' => [
                    'name' => $r['name'],
                    'notes' => $r['notes'],
                    'location_type_id' => (int)$r['location_type_id'],
                    'status_type_id' => (int)$r['status_type_id'],
                    'is_critical' => (int)$r['is_critical'],
                ],
                'geometry' => $geom,
            ];
        }

        return [
            'type' => 'FeatureCollection',
            'features' => $features,
        ];
    }

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

        Yii::$app->db->createCommand()->insert('entity', [
            'entity_type_id' => 1,
        ])->execute();

        $entityId = (int)Yii::$app->db->getLastInsertID();

        Yii::$app->db->createCommand()->insert('location', [
            'location_type_id' => $locationTypeId,
            'name' => $name,
            'notes' => $notes,
            'geometry' => $geomJson,
            'status_type_id' => $statusTypeId,
            'is_critical' => $isCritical,
            'updated_at' => new \yii\db\Expression('CURRENT_TIMESTAMP'),
            'entity_id' => $entityId,
        ])->execute();

        $id = (int)Yii::$app->db->getLastInsertID();

        return ['ok' => true, 'id' => $id];
    }

    public function actionMapUpdate($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $data = json_decode(Yii::$app->request->getRawBody(), true);
        if (!$data) {
            throw new \yii\web\BadRequestHttpException('JSON inválido.');
        }

        $update = [
            'updated_at' => new \yii\db\Expression('CURRENT_TIMESTAMP'),
        ];

        if (array_key_exists('name', $data)) {
            $update['name'] = trim((string)$data['name']) ?: 'Novo local';
        }

        if (array_key_exists('notes', $data)) {
            $update['notes'] = trim((string)$data['notes']) ?: null;
        }

        if (array_key_exists('location_type_id', $data)) {
            $update['location_type_id'] = (int)$data['location_type_id'];
        }

        if (array_key_exists('status_type_id', $data)) {
            $update['status_type_id'] = (int)$data['status_type_id'];
        }

        if (array_key_exists('is_critical', $data)) {
            $update['is_critical'] = (int)$data['is_critical'];
        }

        if (array_key_exists('geometry', $data) && is_array($data['geometry'])) {
            $update['geometry'] = json_encode(
                $data['geometry'],
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            );
        }

        Yii::$app->db->createCommand()
            ->update('location', $update, ['id' => (int)$id])
            ->execute();

        return ['ok' => true];
    }

    public function actionMapDelete($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        Yii::$app->db->createCommand()
            ->delete('location', ['id' => (int)$id])
            ->execute();

        return ['ok' => true];
    }



}
