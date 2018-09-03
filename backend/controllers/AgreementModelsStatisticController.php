<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 30.11.2017
 * Time: 15:03
 */

namespace backend\controllers;


use common\models\agreement_model\AgreementModel;
use common\models\logs\Log;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class AgreementModelsStatisticController extends PageController
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
                            'index',
                            'verification-dates-period',
                            'models-statistics',
                            'model-agreement-timeline',
                            'export-models-data'
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

    /**
     * Получение данных по согласованию заявок, за определенный период
     */
    public function actionVerificationDatesPeriod() {
        $this->_page_header = Yii::t('app', 'Сроки проверки заявок');
        $this->_page_description = Yii::t('app', 'Статистика по заявкам за выбранные период и тип заявки.');

        $this->makeBreadCrumb([
            '' => \Yii::t('app', "Сроки проверки заявок")
        ]);

        return $this->render("verification-dates-period");
    }

    public function actionModelsStatistics() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $result = $this->makeCheckType();
        return [
            'model_list' => $this->renderPartial('partials/_'.Yii::$app->request->post('filter_models_check', 'agreement_period_by_days'),
                [
                    'models' => $result['models_list'],
                    'models_count' => $result['count'],
                    'min_days' => $result['min_days'],
                    'max_days' => $result['max_days'],
                    'models_count_by_days' => isset($result['models_count_by_days']) ? $result['models_count_by_days'] : []
                ]
            ),
            'chart_data' => $result['chart_data']
        ];
    }

    public function actionModelAgreementTimeline() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $timeline_result = Log::getModelAgreementTimeLine(Yii::$app->request->post('model_id'));

        return [
            'content' => $this->renderPartial('partials/_model_agreement_timeline',
                [
                    'timeline_result' => $timeline_result['timeline'],
                    'agreement_days' => $timeline_result['agreement_days_count'],
                ]
            ),
            'chart_data' => $timeline_result['chart_data'],
        ];
    }

    /**
     * Экспорт данных по заявкам полученным за период
     */
    public function actionExportModelsData() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        //return AgreementModel::exportModelsTimeLineStatistics();
        return $this->exportFunction();
    }

    /**
     * Получить функцию фильтра
     */
    private function makeCheckType() {
        $checkType = Yii::$app->request->post('filter_models_check', 'agreement_period_by_days');

        $checkType = implode("", array_map(function($item) {
            return ucfirst($item);
        }, explode("_", $checkType)));

        $checkType = lcfirst($checkType);

        return AgreementModel::$checkType();
    }

    private function exportFunction() {
        $checkType = Yii::$app->request->post('filter_models_check', 'agreement_period_by_days');

        $checkType = implode("", array_map(function($item) {
            return ucfirst($item);
        }, explode("_", $checkType)));

        $checkType = 'export'.$checkType;

        return AgreementModel::$checkType();
    }
}
