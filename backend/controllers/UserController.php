<?php

namespace backend\controllers;

use backend\models\UserForm;
use common\models\Role;
use common\models\User;
use app\models\UserSearch;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $userLogado = Yii::$app->user->identity;

        $roles = Yii::$app->authManager->getRoles();
        $roleFilter = ArrayHelper::map($roles, 'description', 'description');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'userLogado' => $userLogado,
            'roleFilter' => $roleFilter,
        ]);
    }

    public function actionView($id) {
        $model = $this->findModel($id);

        $statusLabel = function ($status) {
            return match ((int)$status) {
                10 => ['label' => 'Ativo', 'class' => 'bg-success'],
                9  => ['label' => 'Inativo', 'class' => 'bg-warning text-dark'],
                0  => ['label' => 'Eliminado', 'class' => 'bg-danger'],   //SOFT DELETE A SER IMPLEMENTADO
                default => ['label' => 'Desconhecido', 'class' => 'bg-secondary'],
            };
        };

        // Description "bonita" da role (RBAC)
        $getUserRoleDescription = function ($userId) {
            $auth = Yii::$app->authManager;

            $roles = $auth->getRolesByUser($userId);
            if (empty($roles)) {
                return null;
            }

            $firstRole = reset($roles);
            $roleObj = $auth->getRole($firstRole->name);

            return $roleObj?->description ?: $firstRole->name;
        };

        // Role ID (name técnico da role no RBAC)
        $getUserRoleName = function ($userId) {
            $auth = Yii::$app->authManager;

            $roles = $auth->getRolesByUser($userId);
            if (empty($roles)) {
                return null;
            }

            $firstRole = reset($roles);
            return $firstRole->name;
        };

        $badge = $statusLabel($model->status);
        $roleDesc = $getUserRoleDescription($model->id);

        return $this->render('view', [
            'model' => $model,
            'badge' => $badge,
            'roleDesc' => $roleDesc,
        ]);
    }

    public function actionCreate()
    {
        $form = new UserForm();
        $roles = Yii::$app->authManager->getRoles();

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            return $this->redirect(['view', 'id' => $form->id]);
        }

        return $this->render('create', [
            'model' => $form,
            'roles' => $roles,
        ]);
    }

    public function actionUpdate($id)
    {
        $user = User::findOne($id);
        if (!$user) {
            throw new \yii\web\NotFoundHttpException();
        }

        $form = new UserForm();
        $form->setUser($user);

        $roles = Yii::$app->authManager->getRoles();

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            return $this->redirect(['view', 'id' => $form->id]);
        }

        return $this->render('update', [
            'model' => $form,
            'roles' => $roles,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionUpdateStatus($id){
        $model = $this->findModel($id);

        if ($model->status == User::STATUS_ACTIVE) {
            $model->status = User::STATUS_INACTIVE;
            Yii::$app->session->setFlash('warning', 'Utilizador desativado!');
        } else {
            $model->status = User::STATUS_ACTIVE;
            Yii::$app->session->setFlash('success', 'Utilizador ativado!');
        }

        $model->save(false); //tem que estar false, se não explode

        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }
}
