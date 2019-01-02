<?php

namespace backend\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 11.10.2018
 * Time: 11:02
 */

class ActivityConsolidatedInformationController extends PageController
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
                        'actions' => [ 'login', 'error', 'export' ],
                        'allow' => true,
                    ],
                    [
                        'actions' => [
                            '',
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

    public function actionExport() {

    }
}
