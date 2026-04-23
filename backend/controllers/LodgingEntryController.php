<?php

namespace backend\controllers;

use common\models\Branch;
use common\models\LodgingEntry;
use app\models\LodgingEntrySearch;
use common\models\Unit;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LodgingEntryController implements the CRUD actions for LodgingEntry model.
 */
class LodgingEntryController extends Controller
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

    public function beforeAction($action) {
        if (in_array($action->id, ['view', 'update'])) {
            return $this->redirect(['site/error-page', 'type' => 'action-unavailable']);
        }

        return parent::beforeAction($action);
    }

    /**
     * Lists all LodgingEntry models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new LodgingEntrySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LodgingEntry model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new LodgingEntry model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($lodging_site_id) {
        $model = new LodgingEntry();
        $model->lodging_site_id = $lodging_site_id;

        //Valor default de current_timestamp.
        $model->checkin_at = date('Y-m-d');
        $unitArray = Unit::dropDown();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['lodging-site/view', 'id' => $model->lodging_site_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'unitArray' => $unitArray,
        ]);
    }

    /**
     * Updates an existing LodgingEntry model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $unitArray = Unit::dropDown();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['lodging-site/view', 'id' => $model->lodging_site_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'unitArray' => $unitArray,
        ]);
    }

    /**
     * Apaga um registo entry existente.
     * Caso seja apagado com sucesso, redireciona para a página de view do lodging-site em questão.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        //Guarda o lodging_site_id antes do registo ser apagado, já que é necessário para o redirecionamento correto.
        $model = $this->findModel($id);
        $viewId = $model->lodging_site_id;

        $this->findModel($id)->delete();

        return $this->redirect(['lodging-site/view', 'id' => $viewId]);
    }

    /**
     * Finds the LodgingEntry model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return LodgingEntry the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LodgingEntry::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    /**
     * Faz o checkout de uma entrada no Lodging-entry.
     * Esta função é invocada a partir do view de cada lodging-site.
     */
    public function actionCheckout($id){
        $model = $this->findModel($id);

        if ($model->checkout_at == null) {
            $model->checkout_at = date('Y-m-d');
        } else {
            Yii::$app->session->setFlash('warning', 'Checkout já foi feito.');
        }

        $model->save(false); //tem que estar false, se não explode

        return $this->redirect(['lodging-site/view', 'id' => $model->lodging_site_id]);
    }
}
