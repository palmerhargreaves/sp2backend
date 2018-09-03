<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 12.06.2018
 * Time: 10:14
 */

namespace backend\controllers;


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
                            'block-inform'
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
     * Информация по блокировке заявок
     */
    public function actionBlockInform() {
        return $this->render('block_inform', [
            'models' => AgreementModelsBlockInformUtils::getBlockInformation()
        ]);
    }
}
