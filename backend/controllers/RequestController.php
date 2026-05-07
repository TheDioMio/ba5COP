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
use yii\filters\AccessControl;
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
                            'roles' => ['request.manage'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                        'accept-request' => ['POST'],
                        'deny-request' => ['POST'],
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
        // Por defeito, abre logo nos pendentes
        $estado = Yii::$app->request->get('estado', 'pending');

        $searchModelInternos = new RequestSearch();
        $searchModelInternos->fixedIsExternal = 0;
        $searchModelInternos->customFormName = 'RequestSearchInternos';

        $searchModelExternos = new RequestSearch();
        $searchModelExternos->fixedIsExternal = 1;
        $searchModelExternos->customFormName = 'RequestSearchExternos';

        $dataProviderInternos = $searchModelInternos->search(Yii::$app->request->queryParams);
        $dataProviderExternos = $searchModelExternos->search(Yii::$app->request->queryParams);

        // IDs dos estados
        $novoId = $this->getRequestStatusId('NOVO');
        $emAnaliseId = $this->getRequestStatusId('EM ANÁLISE');
        $aprovadoId = $this->getRequestStatusId('APROVADO');
        $rejeitadoId = $this->getRequestStatusId('REJEITADO');

        // Aplica filtro por grupo
        switch ($estado) {
            case 'approved':
                if ($aprovadoId !== null) {
                    $dataProviderInternos->query->andWhere(['status_type_id' => $aprovadoId]);
                    $dataProviderExternos->query->andWhere(['status_type_id' => $aprovadoId]);
                }
                break;

            case 'rejected':
                if ($rejeitadoId !== null) {
                    $dataProviderInternos->query->andWhere(['status_type_id' => $rejeitadoId]);
                    $dataProviderExternos->query->andWhere(['status_type_id' => $rejeitadoId]);
                }
                break;

            case 'pending':
            default:
                $pendingIds = array_filter([$novoId, $emAnaliseId]);

                if (!empty($pendingIds)) {
                    $dataProviderInternos->query->andWhere(['status_type_id' => $pendingIds]);
                    $dataProviderExternos->query->andWhere(['status_type_id' => $pendingIds]);
                }

                $estado = 'pending';
                break;
        }

        $priorityList = Priority::dropDown();
        $requestTypeList = RequestType::dropDown();

        return $this->render('index', [
            'searchModelInternos' => $searchModelInternos,
            'searchModelExternos' => $searchModelExternos,
            'dataProviderInternos' => $dataProviderInternos,
            'dataProviderExternos' => $dataProviderExternos,
            'priorityList' => $priorityList,
            'requestTypeList' => $requestTypeList,
            'estado' => $estado,
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
        $model = $this->findModel($id);

        $novoId = $this->getRequestStatusId('NOVO');
        $emAnaliseId = $this->getRequestStatusId('EM ANÁLISE');

        // Quando o utilizador abre os detalhes de um pedido NOVO,
        // passa automaticamente para EM ANÁLISE
        if (
            $novoId !== null &&
            $emAnaliseId !== null &&
            (int)$model->status_type_id === (int)$novoId
        ) {
            $model->status_type_id = $emAnaliseId;
            $model->save(false, ['status_type_id']);
        }

        return $this->render('view', [
            'model' => $model,
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
                    return $this->redirect(['index']);
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

    private function getRequestStatusId($description)
    {
        return StatusType::find()
            ->select('id')
            ->where([
                'entity_type_id' => Entity::REQUEST_ID,
                'description' => $description,
            ])
            ->scalar();
    }

    public function actionAcceptRequest($id)
    {
        $model = $this->findModel($id);

        $aprovadoId = $this->getRequestStatusId('APROVADO');

        if ($aprovadoId !== null) {
            $model->status_type_id = $aprovadoId;
            $model->save(false, ['status_type_id']);
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionDenyRequest($id)
    {
        $model = $this->findModel($id);

        $rejeitadoId = $this->getRequestStatusId('REJEITADO');

        if ($rejeitadoId !== null) {
            $model->status_type_id = $rejeitadoId;
            $model->save(false, ['status_type_id']);
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }
}
