<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 12.06.2018
 * Time: 10:14
 */

namespace backend\controllers;


use common\models\agreement_model\statistic\ModelsCompletedCountUtil;
use common\models\model\block_inform\AgreementModelsBlockInformUtils;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class ModelsController extends PageController
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
                            'block-inform',
                            'completed-calculate-count',
                            'on-change-year'
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
     * Вычисление количетсва выполненных заявок за выбранный период
     */
    public function actionCompletedCalculateCount() {
        if (\Yii::$app->request->isPost && \Yii::$app->request->isAjax) {
            $models_utils = new ModelsCompletedCountUtil(\Yii::$app->request);

            $filter_result = $models_utils->filterData();
            return $this->renderPartial('partials/_completed_models_count_by_'.$filter_result['check_by'], [
                'result' => $filter_result
            ]);
        }

        return $this->render('completed-calculate-count', [
            'models_completed_count_util' => new ModelsCompletedCountUtil()
        ]);
    }

    /**
     * Обработка выбора года
     */
    public function actionOnChangeYear() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $models_utils = new ModelsCompletedCountUtil(\Yii::$app->request);

        return [
            'quarters_list' => $this->renderPartial('partials/_quarters_list', [ 'quarters_list' => $models_utils->getQuartersList() ]),
            'months_list' => $this->renderPartial('partials/_months_list.php', [ 'quarters_list' => $models_utils->getQuartersList() ])
        ];
    }

    /**
     * Информация по блокировке заявок
     */
    public function actionBlockInform() {
        return $this->render('block_inform', [
            'models' => AgreementModelsBlockInformUtils::getBlockInformation()
        ]);
    }
}
