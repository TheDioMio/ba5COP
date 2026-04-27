<?php

namespace backend\controllers;

use common\models\Branch;
use common\models\LodgingEntry;
use app\models\LodgingEntrySearch;
use common\models\Unit;
use Yii;
use yii\filters\AccessControl;
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
    public function actionCreate($lodging_site_id)
    {
        $model = new LodgingEntry();
        $model->lodging_site_id = $lodging_site_id;
        $model->checkin_at = date('Y-m-d H:i:s');

        $unitArray = Unit::dropDown();

        if ($this->request->isPost) {
            $model->load($this->request->post());

            $lodgingSite = $model->lodgingSite;

            if ($model->people_count > $lodgingSite->capacity_available) {
                $model->addError('people_count', 'Não há camas disponíveis suficientes.');
            } else {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    if (!$model->save()) {
                        throw new \Exception('Erro ao guardar entrada.');
                    }

                    $lodgingSite->capacity_available -= $model->people_count;

                    if (!$lodgingSite->save(false)) {
                        throw new \Exception('Erro ao atualizar capacidade disponível.');
                    }

                    $transaction->commit();

                    return $this->redirect(['lodging-site/view', 'id' => $model->lodging_site_id]);
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    throw $e;
                }
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
    public function actionCheckout($id)
    {
        $model = $this->findModel($id);

        if ($model->checkout_at !== null) {
            Yii::$app->session->setFlash('warning', 'Checkout já foi feito.');

            return $this->redirect(['lodging-site/view', 'id' => $model->lodging_site_id]);
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $model->checkout_at = date('Y-m-d H:i:s');

            if (!$model->save(false)) {
                throw new \Exception('Erro ao guardar checkout.');
            }

            $lodgingSite = $model->lodgingSite;
            $lodgingSite->capacity_available += $model->people_count;

            if ($lodgingSite->capacity_available > $lodgingSite->capacity_total) {
                $lodgingSite->capacity_available = $lodgingSite->capacity_total;
            }

            if (!$lodgingSite->save(false)) {
                throw new \Exception('Erro ao atualizar capacidade disponível.');
            }

            $transaction->commit();

            Yii::$app->session->setFlash('success', 'Checkout realizado com sucesso.');
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $this->redirect(['lodging-site/view', 'id' => $model->lodging_site_id]);
    }
}
