<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 05.09.2018
 * Time: 21:08
 */

namespace backend\controllers;

use common\models\activity\ActivityAgreementByUser;
use common\models\activity\ActivityExtendedStatisticSections;
use common\models\activity\ActivityExtendedStatisticSectionsTemplates;

use common\models\activity\fields\ActivityExtendedStatisticFields;
use common\models\activity\statistic\ActivitySettingsBlock;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;


class ActivityStatisticController extends PageController
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
                            'show-statistic-config',
                            'activity-statistic-disable-block',
                            'activity-statistic-activate-block',
                            'load-block-data',
                            'add-block-field',
                            'edit-settings',
                            'delete-block-field'
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
     *
     */
    public function actionShowStatisticConfig ()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return $this->renderPartial('partials/_activity_config_statistic', [ 'section_templates' => ActivityExtendedStatisticSectionsTemplates::getList($this->getActivity()) ]);
    }

    /**
     * Активировать блок статистики
     */
    public function actionActivityStatisticDisableBlock ()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [ 'success' => ActivityExtendedStatisticSections::disableSection(),
            'html' => Yii::t('app', 'Блок успешно отключен!'),
            'block_html' => $this->renderPartial('partials/blocks/_block_item', [ 'section_template' => ActivityExtendedStatisticSectionsTemplates::getBlock(\Yii::$app->request->post('section_template_id'), $this->getActivity()) ]),
            'section_template_id' => \Yii::$app->request->post('section_template_id'),
        ];
    }

    /**
     * Отключить блок стиатисики
     */
    public function actionActivityStatisticActivateBlock ()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $section = ActivityExtendedStatisticSectionsTemplates::activateSection();
        if ($section) {
            return [ 'success' => true,
                'html' => $section->render($this),
                'block_html' => $this->renderPartial('partials/blocks/_block_item', [ 'section_template' => ActivityExtendedStatisticSectionsTemplates::getBlock(\Yii::$app->request->post('section_template_id'), $this->getActivity()) ]),
                'section_template_id' => \Yii::$app->request->post('section_template_id')
            ];
        }

        return [ 'success' => false ];
    }

    /**
     * Загружаем данные по блоку
     */
    public function actionLoadBlockData ()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $section = ActivityExtendedStatisticSectionsTemplates::getSection();
        if ($section) {
            return [ 'success' => true, 'html' => $section->render($this), 'html_fields' => $section->renderFields($this), 'section_id' => $section->id ];
        }

        return [ 'success' => false ];
    }

    /**
     * Добавляем к блоку поля
     * @return array
     */
    public function actionAddBlockField ()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $section = ActivityExtendedStatisticSectionsTemplates::getSection();
        if ($section && Yii::$app->request->isPost) {
            return $section->addBlockField($this);
        }

        return [ 'success' => false ];
    }

    /**
     * Редактирование параметров блока
     * @param $id
     * @return array
     */
    public function actionEditSettings ( $id )
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = ActivitySettingsBlock::findOne([ 'id' => $id ]);

        if ($model && Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            return [ 'success' => $model->save(), 'message' => Yii::t('app', 'Параметры блока успешно сохранены.') ];
        }

        return [ 'success' => false ];
    }

    /**
     * Удаление поля
     */
    public function actionDeleteBlockField() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $field = ActivityExtendedStatisticFields::find()->where(['id' => \Yii::$app->request->get('id')])->one();
        $section = ActivityExtendedStatisticSectionsTemplates::getSection();

        if ($field && $section) {
            ActivityExtendedStatisticFields::deleteAll(['id' => $field->id]);

            return [ 'success' => true, 'message' => 'Поле успешно удалено.', 'html' => $section->render($this), 'html_fields' => $section->renderFields($this), 'section_id' => $section->id ];
        }

        return [ 'success' => false, 'message' => 'Ошибка удаления поля.' ];
    }
}
