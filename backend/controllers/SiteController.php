<?php
namespace backend\controllers;

use backend\components\TasksUtils;
use backend\models\SettingsForm;
use backend\models\UserSearch;
use common\models\agreement_model\AgreementModelSearch;
use common\models\Settings;
use frontend\models\SearchForm;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\user\LoginForm;

/**
 * Site controller
 */
class SiteController extends PageController
{
    const PAGE_URL = '/admin';

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
                        'actions' => ['logout', 'index', 'view', 'update', 'create', 'delete', 'search', 'settings'],
                        'allow' => true,
                        'roles' => ['@'],
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

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->_current_page = self::PAGE_URL;

        $searchModelsProvider = new AgreementModelSearch();
        $dataModelsProvider = $searchModelsProvider->search(Yii::$app->request->queryParams);
        $dataModelsProvider->pagination->pageSizeLimit = null;

        return $this->render('index', ['searchModelsProvider' => $searchModelsProvider, 'dataModelsProvider' => $dataModelsProvider]);
    }

    /**
     * Config site settings
     */
    public function actionSettings() {
        $settings_form = new SettingsForm();
        $settings_model = new Settings();

        $settings_model->loadData();

        if (Yii::$app->request->isPost) {
            if ($settings_form->load(Yii::$app->request->post(), 'Settings') && $settings_form->validate()) {
                $settings_form->save();

                $settings_model->loadData();

                Yii::$app->session->setFlash('success', Yii::t('app', 'Параметры сайта успешно сохранены.'));
            } else if ($settings_form->hasErrors()) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Ошибка сохранения параметров сайта.'));
            }
        }

        //var_dump($settings_form->hasErrors());

        return $this->render('/site/settings', ['model' => $settings_model]);
    }

}
