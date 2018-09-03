<?php

namespace backend\controllers;

use common\models\ContactsSearch;
use common\models\user\User;
use richardfan\sortable\SortableAction;
use Yii;
use common\models\Contacts;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContactsController implements the CRUD actions for Contacts model.
 */
class ContactsController extends PageController
{
    /**
     * @inheritdoc
     */
    public function behaviors ()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [ 'login', 'error' ],
                        'allow' => true,
                    ],
                    [
                        'actions' => [
                            'logout',
                            'index',
                            'user-data',
                            'delete',
                            'update',
                            'sortItem',
                            'search-user-by-name'
                        ],
                        'allow' => true,
                        'roles' => [ '@' ],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions ()
    {
        return [
            'sortItem' => [
                'class' => SortableAction::className(),
                'activeRecordClassName' => Contacts::className(),
                'orderColumn' => 'position'
            ]
        ];
    }

    /**
     * Lists all Contacts models.
     * @return mixed
     */
    public function actionIndex ()
    {
        $this->_page_header = Yii::t('app', 'Контакты');

        $this->makeBreadCrumb([
            '' => \Yii::t('app', "Список контактов")
        ]);

        $contactModel = new Contacts();

        if (Yii::$app->request->isPost) {
            if ($contactModel->load(Yii::$app->request->post()) && $contactModel->validate()) {
                unset($contactModel->photo);

                if (!is_null($contactModel->user_id)) {
                    $user = User::find()->where(['id' => $contactModel->user_id])->one();

                    if ($user) {
                        $contactModel->name = sprintf('%s %s', $user->name, $user->surname);
                    }
                }

                $contactModel->savePhoto();
                $contactModel->save();

                return $this->redirect(Url::to([ 'index' ]));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Ошибка сохранения контакта!'));
            }
        }

        $searchProvider = new ContactsSearch();
        $dataProvider = $searchProvider->search(Yii::$app->request->queryParams);

        return $this->render('index', [ 'searchProvider' => $searchProvider, 'dataProvider' => $dataProvider, 'contactModel' => $contactModel ]);
    }

    /**
     * Updates an existing Contacts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate ( $id )
    {
        $this->_page_header = Yii::t('app', 'Контакты / Редактирование профиля');

        $this->makeBreadCrumb([
            Url::to(['/contacts']) => 'Список контактов',
            '' => \Yii::t('app', "Редактирование профиля")
        ]);

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            unset($model->photo);

            if ($model->save()) {
                $model->savePhoto();
            }

            Yii::$app->session->setFlash('success', 'Профиль успешно сохранен.');

            return $this->redirect([ 'update', 'id' => $model->id ]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Contacts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete ( $id )
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $this->findModel($id)->delete();

        return ['success' => true];
    }

    /**
     * Finds the Contacts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Contacts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel ( $id )
    {
        if (( $model = Contacts::findOne($id) ) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUserData ()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $contact = Contacts::find()->where(['id' => Yii::$app->request->post('item_id')])->one();

        return [
            'success' => true,
            'content' => $this->renderPartial('partials/_contact', [ 'contact' => $contact ]),
        ];
    }

    public function actionSearchUserByName() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [
            'users_list' => $this->renderPartial('partials/_users_search_list.php',
                [
                    'users' => User::find()->where(['like', 'name', Yii::$app->request->post('user_name')])->all()
                ]
            )
        ];
    }
}
