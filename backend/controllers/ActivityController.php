<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 22.11.2017
 * Time: 17:10
 */

namespace backend\controllers;


use common\models\activity\Activity;
use common\models\activity\ActivityAgreementByUser;
use common\models\activity\ActivitySearch;
use common\models\activity\ActivitySpecialAgreementUsersList;
use common\models\activity\ActivitySpecialists;
use common\models\activity\ActivityStatisticPreCheckSearch;
use common\models\activity\ActivityStatisticPreCheckUsers;
use common\models\activity\ActivityTypeCompanyImages;
use common\models\activity\utils\ActivitiesStatistics;
use common\models\logs\Log;
use richardfan\sortable\SortableAction;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;

class ActivityController extends PageController
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
                            'info',
                            'list',
                            'show-config-options',
                            'get-statistics',
                            'export-statistics',
                            'config-export-statistics',
                            'bind-unbind-specialist',
                            'config-simple-statistic-fields',
                            'config-simple-statistic',
                            'sort-statistic-fields',
                            'save-statistic-config',
                            'statistic-pre-check',
                            'show-history-pre-check',
                            'pre-check-user-statistic',
                            'config-special-agreement',
                            'special-agreement-user',
                            'config-agreement-by-user',
                            'allow-deny-special-agreement',
                            'allow-deny-agreement-by-user',
                            'agreement-by-importer-user',
                            'show-statistic-config',
                            'activity-statistic-disable-block',
                            'activity-statistic-activate-block',
                            'load-block-data',
                            'add-block-field',
                            'validate-block-data',
                            'upload-activity-company-type-image',
                            'settings'
                        ],
                        'allow' => true,
                        'roles' => [ '@' ],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => [ 'post' ],
                ],
            ],
        ];
    }

    public function actions ()
    {
        return [
            'sortItem' => [
                'class' => SortableAction::className(),
                'activeRecordClassName' => Activity::className(),
                'orderColumn' => 'position'
            ]
        ];

        //parent::actions(); // TODO: Change the autogenerated stub
    }

    public function actionInfo ()
    {
        $this->_page_header = Yii::t('app', 'Параметры активности');

        return $this->render('info');
    }

    public function actionList ()
    {
        $this->_page_header = Yii::t('app', 'Список активностей');

        $activitySearch = new ActivitySearch();
        $dataProvider = $activitySearch->search(Yii::$app->request->queryParams);

        $activity_company_type_image_model = new ActivityTypeCompanyImages();

        return $this->render('index', [ 'searchProvider' => $activitySearch, 'dataProvider' => $dataProvider, 'activity_company_type_image_model' => $activity_company_type_image_model ]);
    }

    public function actionShowConfigOptions ()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [
            'content' => $this->renderPartial('partials/_show_config_options', [ 'activity' => $this->getActivity() ])
        ];
    }

    public function actionGetStatistics ()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return ActivitiesStatistics::getStatisticsByActivities();
    }

    public function actionExportStatistics ()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return Activity::exportStatisticData();
    }

    public function actionConfigExportStatistics ()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return $this->renderPartial('partials/_export_statistic_by_steps', [ 'activity' => $this->getActivity() ]);
    }

    public function actionBindUnbindSpecialist ()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        ActivitySpecialists::bindData();

        return [ 'result' => true ];
    }

    public function actionConfigSimpleStatistic() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return $this->renderPartial('partials/_config_simple_statistic', [ 'activity' => $this->getActivity() ]);
    }

    public function actionSaveStatisticConfig() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return Activity::saveStatisticConfig();
    }

    public function actionConfigSimpleStatisticFields() {
        return $this->render('partials/_config_simple_statistic_fields', [ 'activity' => $this->getActivity() ]);
    }

    public function actionSortStatisticFields() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [];
    }

    /**
     * Вывод доп. информации по согласованию статистики системными пользователями
     */
    public function actionStatisticPreCheck() {
        $this->_page_header = Yii::t('app', 'Список активностей');

        $searchProvider = new ActivityStatisticPreCheckSearch();
        $dataProvider = $searchProvider->search(Yii::$app->request->queryParams);

        return $this->render('statistic-pre-check', [ 'searchProvider' => $searchProvider, 'dataProvider' => $dataProvider ]);
    }

    public function actionShowHistoryPreCheck() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $history_items = Log::find()
            ->where(['object_id' => Yii::$app->request->post('id')])
            ->where(['object_type' => 'activity_statistic'])
            ->orderBy(['id' => SORT_DESC])->all();

        return [
            'content' => $this->renderPartial('partials/_activity_statistic_history_pre_check', [
                'items' => $history_items
            ])
        ];
    }

    /**
     * ВЫбор пользователей для согласования статистики
     */
    public function actionPreCheckUserStatistic() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $activity_id = Yii::$app->request->post('activity_id');
        $user_id = Yii::$app->request->post('user_id');

        if (ActivityStatisticPreCheckUsers::find()->where(['user_id' => $user_id, 'activity_id' => $activity_id])->count() == 0) {
            $item = new ActivityStatisticPreCheckUsers();
            $item->activity_id = $activity_id;
            $item->user_id = $user_id;
            $item->save(false);
        } else {
            ActivityStatisticPreCheckUsers::deleteAll(['user_id' => $user_id, 'activity_id' => $activity_id]);
        }

        return [ 'success' => true ];
    }

    /**
     *
     * @return string
     */
    public function actionConfigSpecialAgreement() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return $this->renderPartial('partials/_config_special_agreement', [ 'activity' => $this->getActivity() ]);
    }

    /**
     *
     */
    public function actionSpecialAgreementUser() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $activity_id = Yii::$app->request->post('activity_id');
        $user_id = Yii::$app->request->post('user_id');

        if (ActivitySpecialAgreementUsersList::find()->where(['user_id' => $user_id, 'activity_id' => $activity_id])->count() == 0) {
            $item = new ActivitySpecialAgreementUsersList();
            $item->activity_id = $activity_id;
            $item->user_id = $user_id;
            $item->save(false);
        } else {
            ActivitySpecialAgreementUsersList::deleteAll(['user_id' => $user_id, 'activity_id' => $activity_id]);
        }

        return [ 'success' => true ];
    }

    /**
     *
     */
    public function actionAllowDenySpecialAgreement() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $activity = $this->getActivity();
        $activity->allow_special_agreement = Yii::$app->request->post('allow_deny');
        $activity->save(false);

        return ['success' => true];
    }

    /**
     *
     */
    public function actionAllowDenyAgreementByUser() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $activity = $this->getActivity();
        $activity->allow_agreement_by_one_user = Yii::$app->request->post('allow_deny');
        $activity->save(false);

        return ['success' => true];
    }

    /**
     *
     * @return string
     */
    public function actionConfigAgreementByUser() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return $this->renderPartial('partials/_config_agreement_by_user', [ 'activity' => $this->getActivity() ]);
    }

    /**
     *
     */
    public function actionAgreementByImporterUser() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $activity_id = Yii::$app->request->post('activity_id');
        $user_id = Yii::$app->request->post('user_id');

        if (ActivityAgreementByUser::find()->where(['user_id' => $user_id, 'activity_id' => $activity_id])->count() == 0) {
            $item = new ActivityAgreementByUser();
            $item->activity_id = $activity_id;
            $item->user_id = $user_id;
            $item->user_type = ActivityAgreementByUser::USER_TYPE_IMPORTER;
            $item->save(false);
        } else {
            ActivityAgreementByUser::deleteAll(['user_id' => $user_id, 'activity_id' => $activity_id]);
        }

        return [ 'success' => true ];
    }

    /**
     * Загрузка изоюражния для компании активности
     */
    public function actionUploadActivityCompanyTypeImage() {

        if (Yii::$app->request->isPost) {
            $id = Yii::$app->request->post('ActivityTypeCompanyImages')['id'];

            $activity_company_type_image_model = ActivityTypeCompanyImages::findOne(['id' => $id]);
            if (is_null($activity_company_type_image_model)) {
                $activity_company_type_image_model = new ActivityTypeCompanyImages();
            }

            if ($activity_company_type_image_model->load(Yii::$app->request->post()) && $activity_company_type_image_model->validate()) {

                if (!$activity_company_type_image_model->upload()) {
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Ошибка сохранения!'));

                    return $this->redirect(Url::to([ 'list' ]));
                }

                Yii::$app->session->setFlash('success', Yii::t('app', 'Изображение успешно сохранено!'));

                return $this->redirect(Url::to([ 'list' ]));
            } else {

                Yii::$app->session->setFlash('error', Yii::t('app', 'Ошибка сохранения!'));
            }
        }

        return $this->redirect(Url::to([ 'list' ]));
    }

    public function actionSettings() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (Yii::$app->request->isPost) {
            $activity = Activity::findOne(['id' => Yii::$app->request->post('id')]);

            if ($activity->load(Yii::$app->request->post())) {
                $activity->save(false);

                return [ 'success' => true, 'message' => Yii::t('app', 'Параметры успешно сохранены.') ];
            }
        }

        return [ 'success' => false ];
    }
}
