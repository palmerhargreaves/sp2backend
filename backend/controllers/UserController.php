<?php

namespace backend\controllers;

use Yii;
use common\models\user\User;
use backend\models\UserForm;
use backend\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'view', 'update', 'create', 'delete'],
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
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'roles' => $this->getAvailableRoles()
        ]);
    }
    
    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserForm(new User());

        $model->setScenario('create');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            Yii::$app->session->setFlash('success', Yii::t('app', 'Пользователь "{name}" успешно добавлен', ['name' => $model->getUser()->fullName]));
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'roles' => $this->getAvailableRoles()
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = new UserForm($this->findModel($id));

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            Yii::$app->session->setFlash('success', Yii::t('app', 'Пользователь "{name}" успешно изменен', ['name' => $model->getUser()->fullName]));
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'user' => $model->getUser(),
                'model' => $model,
                'roles' => $this->getAvailableRoles()
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if($model) {
            //$clientsCount = count($model->clients);

            /*if($clientsCount) {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Пользователь не может быть удален, так как имеет {count} клиентов', ['count' => $clientsCount]));
            } else */
            if($model->isAdmin) {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Нельзя удалить администратора'));
            } else {
                Yii::$app->authManager->revokeAll($id);
                $model->delete();
                Yii::$app->session->setFlash('success', Yii::t('app', 'Пользователь успешно удален'));
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @return array
     */
    protected function getAvailableRoles()
    {
        $roles = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name');
        foreach ($roles as $k => &$role) {
            if($role === 'user') {
                unset($roles[$k]);
                continue;
                // remove User role (ona ni nada)
            }

            $role = ucfirst($role);
        }
        return $roles;
    }
}
