<?php

namespace backend\controllers;

use app\models\LodgingSiteSearch;
use common\models\LodgingEntry;
use common\models\LodgingSite;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * LodgingSiteController implements the CRUD actions for LodgingSite model.
 */
class LodgingSiteController extends Controller
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
                        'delete' => ['POST'],
                        'map-create' => ['POST'],
                        'map-update' => ['POST'],
                        'map-delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all LodgingSite models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new LodgingSiteSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LodgingSite model.
     *
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $entriesDataProvider = new ActiveDataProvider([
            'query' => LodgingEntry::find()
                ->where(['lodging_site_id' => $model->id])
                ->orderBy(['id' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('view', [
            'model' => $model,
            'entriesDataProvider' => $entriesDataProvider,
        ]);
    }

    /**
     * Creates a new LodgingSite model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new LodgingSite();

        if ($this->request->isPost) {
            $model->load($this->request->post());

            // Se não vier capacidade disponível, assume a total
            if ($model->capacity_available === null || $model->capacity_available === '') {
                $model->capacity_available = $model->capacity_total;
            }

            if ($model->save()) {
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
     * Updates an existing LodgingSite model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
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
     * Deletes an existing LodgingSite model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
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
     * Cria um novo lodging_site via mapa.
     */
    public function actionMapCreate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $data = json_decode(Yii::$app->request->getRawBody(), true);

        if (!$data) {
            throw new \yii\web\BadRequestHttpException('JSON inválido.');
        }

        $name = trim((string)($data['name'] ?? 'Novo alojamento'));
        $capacityTotal = (int)($data['capacity_total'] ?? 0);
        $capacityAvailable = array_key_exists('capacity_available', $data)
            ? (int)$data['capacity_available']
            : $capacityTotal;
        $notes = trim((string)($data['notes'] ?? '')) ?: null;
        $geometry = $data['geometry'] ?? null;

        if (!$geometry || !is_array($geometry)) {
            throw new \yii\web\BadRequestHttpException('geometry em falta.');
        }

        $geomJson = json_encode($geometry, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        Yii::$app->db->createCommand()->insert('lodging_site', [
            'name' => $name,
            'capacity_total' => $capacityTotal,
            'capacity_available' => $capacityAvailable,
            'notes' => $notes,
            'geometry' => $geomJson,
        ])->execute();

        $id = (int)Yii::$app->db->getLastInsertID();

        return [
            'ok' => true,
            'id' => $id,
        ];
    }

    /**
     * Atualiza atributos e/ou geometria de um lodging_site via mapa.
     *
     * @param int $id
     * @return array
     */
    public function actionMapUpdate($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $data = json_decode(Yii::$app->request->getRawBody(), true);

        if (!$data) {
            throw new \yii\web\BadRequestHttpException('JSON inválido.');
        }

        $update = [];

        if (array_key_exists('name', $data)) {
            $update['name'] = trim((string)$data['name']) ?: 'Novo alojamento';
        }

        if (array_key_exists('capacity_total', $data)) {
            $update['capacity_total'] = (int)$data['capacity_total'];
        }

        if (array_key_exists('capacity_available', $data)) {
            $update['capacity_available'] = (int)$data['capacity_available'];
        }

        if (array_key_exists('notes', $data)) {
            $update['notes'] = trim((string)$data['notes']) ?: null;
        }

        if (array_key_exists('geometry', $data) && is_array($data['geometry'])) {
            $update['geometry'] = json_encode(
                $data['geometry'],
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            );
        }

        if (!empty($update)) {
            Yii::$app->db->createCommand()
                ->update('lodging_site', $update, ['id' => (int)$id])
                ->execute();
        }

        return ['ok' => true];
    }

    /**
     * Apaga um lodging_site via mapa.
     *
     * @param int $id
     * @return array
     */
    public function actionMapDelete($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        Yii::$app->db->createCommand()
            ->delete('lodging_site', ['id' => (int)$id])
            ->execute();

        return ['ok' => true];
    }

    /**
     * Finds the LodgingSite model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id ID
     * @return LodgingSite the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LodgingSite::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}