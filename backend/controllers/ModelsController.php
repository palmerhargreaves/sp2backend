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
                            'completed-calculate-count'
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

            return $this->renderPartial('partials/_completed_models_count', [
                'items_list' => $models_utils->filterData()
            ]);
        }

        return $this->render('completed-calculate-count', [
            'models_completed_count_util' => new ModelsCompletedCountUtil()
        ]);
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
