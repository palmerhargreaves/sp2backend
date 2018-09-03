<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 12.12.2017
 * Time: 5:16
 */

namespace backend\controllers;


use common\models\activity\Activity;
use common\models\slots\QuartersSlotActivities;
use common\models\slots\QuartersSlots;
use common\utils\D;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class MandatoryActivitiesController extends PageController
{
    private $current_quarter = 0;
    private $current_year = 0;

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
                        'actions' => ['index', 'add-slot', 'remove-slot', 'config-slot', 'slot-activity-set-unset', 'change-year-quarter'],
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

    public function actionIndex()
    {
        $this->_page_header = Yii::t('app', 'Обязательные активности (слоты)');
        $this->_page_description = Yii::t('app', 'Успарвление привязкой слотов');

        $this->makeBreadCrumb([
            '' => \Yii::t('app', $this->_page_header)
        ]);

        $this->outputFilter();

        return $this->render('index', [
                'current_year' => $this->current_year,
                'current_quarter' => $this->current_quarter,
                'slots_list' => $this->renderPartial('partials/slots-list', [
                    'slots' => QuartersSlots::getSlotsList($this->current_year, $this->current_quarter),
                    'quarter' => $this->current_quarter,
                    'year' => $this->current_year
                ]),
                'quarters' => range(1, 4),
                'years' => range(date('Y') - 2, date('Y') + 2)
            ]
        );
    }

    /**
     * Add new slot
     * @return array
     */
    public function actionAddSlot()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $this->outputFilter();

        QuartersSlots::addSlot($this->current_year, $this->current_quarter);

        return ['success' => true, 'content' => $this->renderPartial('partials/slots-list',
            [
                'slots' => QuartersSlots::getSlotsList($this->current_year, $this->current_quarter),
                'quarter' => $this->current_quarter,
                'year' => $this->current_year
            ])
        ];
    }

    /**
     *
     */
    public function actionConfigSlot()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [
            'success' => true,
            'content' => $this->renderPartial('partials/slot-config',
                [
                    'slot' => QuartersSlots::findOne(['id' => Yii::$app->request->post('id')]),
                    'activities' => Activity::find()->where(['mandatory_activity' => true])->orderBy(['id' => SORT_DESC])->all(),
                ]
            )
        ];
    }

    /**
     * Remove slot
     * @return array
     */
    public function actionRemoveSlot()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return ['success' => QuartersSlots::deleteAll(['id' => Yii::$app->request->post('id')])];
    }

    /**
     * Bind / Unbind activity in slot
     */
    public function actionSlotActivitySetUnset() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $slot = QuartersSlots::findOne(['id' => Yii::$app->request->post("slot_id")]);
        if (!$slot) {
            return [ "success" => false ];
        }

        $slot->setUnsetActivity(Yii::$app->request->post("activity_id"), Yii::$app->request->post("set"));

        return [
            'content' => $this->renderPartial('partials/_slot-activities', ['activities' => $slot->activities, 'slot' => $slot]),
            'success' => true
        ];
    }

    /**
     * Change year / quarter
     */
    public function actionChangeYearQuarter() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $this->outputFilter();

        return [
                'slots_list' => $this->renderPartial('partials/slots-list', [
                    'slots' => QuartersSlots::getSlotsList($this->current_year, $this->current_quarter),
                    'quarter' => $this->current_quarter,
                    'year' => $this->current_year
                ]),
            ];
    }

    private function outputFilter()
    {
        $this->current_year = $this->getFilterYear();
        $this->current_quarter = $this->getFilterQuarter();
    }

    private function getFilterYear()
    {
        return \Yii::$app->request->isPost
            ? \Yii::$app->request->post('slot_year', D::getYear(D::getCurrentDate()))
            : \Yii::$app->request->get('slot_year', D::getYear(D::getCurrentDate()));
    }

    private function getFilterQuarter()
    {
        return \Yii::$app->request->isPost
            ? \Yii::$app->request->post('slot_quarter', D::getQuarter(D::getCurrentDate()))
            : \Yii::$app->request->get('slot_quarter', D::getQuarter(D::getCurrentDate()));
    }
}
