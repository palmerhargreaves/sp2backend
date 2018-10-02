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

use common\models\activity\ActivityStatisticsPeriods;
use common\models\activity\fields\ActivityExtendedStatisticFields;
use common\models\activity\fields\ActivityExtendedStatisticFieldsCalculated;
use common\models\activity\fields\blocks\ActivityCalculatedFieldBlock;
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
                            'delete-block-field',
                            'add-new-formula',
                            'save-field-data',
                            'delete-formula-field',
                            'add-new-calculated-field',
                            'edit-calculated-field',
                            'bind-activity-statistic-quarter',
                            'show-config'
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

        return $this->renderPartial('partials/_activity_config_statistic', [
                'activity' => $this->getActivity(),
                'section_templates' => ActivityExtendedStatisticSectionsTemplates::getList($this->getActivity()),
                'statistic_config_content' => $this->renderPartial('partials/_statistic-options', [ 'activity' => $this->getActivity() ])
            ]
        );
    }

    /**
     * Активировать блок статистики
     */
    public function actionActivityStatisticDisableBlock ()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [ 'success' => ActivityExtendedStatisticSections::disableSection(),
            'html' => $this->renderPartial('partials/_statistic-options', [ 'activity' => $this->getActivity() ]),
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
    public function actionDeleteBlockField ()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $field = ActivityExtendedStatisticFields::find()->where([ 'id' => \Yii::$app->request->get('id') ])->one();
        $section = ActivityExtendedStatisticSectionsTemplates::getSection();

        if ($field && $section) {
            ActivityExtendedStatisticFields::deleteAll([ 'id' => $field->id ]);

            return [
                'success' => true,
                'message' => 'Поле успешно удалено.',
                'html' => $section->render($this), 'html_fields' => $section->renderFields($this),
                'section_id' => $section->id,
                'fields_count' => $section->getFieldsCount(),
                'calculated_fields_count' => $section->getCalculatedFieldsCount()
            ];
        }

        return [ 'success' => false, 'message' => 'Ошибка удаления поля.' ];
    }

    public function actionAddNewCalculatedField ()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $result = ActivityExtendedStatisticFields::addNewCalculatedField(Yii::$app->request->post());
        if ($result[ 'success' ]) {
            $section = $result[ 'section' ];

            return array_merge($result, [ 'html_fields' => $section->renderFields($this), 'section_id' => $section->id ]);
        }

        return $result;
    }

    public function actionEditCalculatedField ()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $section = ActivityExtendedStatisticSectionsTemplates::getSection();
        $calculated_field = ActivityExtendedStatisticFields::findOne([ 'id' => Yii::$app->request->post('field_id') ]);
        if ($calculated_field) {
            return [
                'success' => true,
                'section_id' => $section->id,
                'html' => $this->renderPartial('partials/_calculated_field_form', [ 'field' => $calculated_field, 'section' => $section, 'calculated_model' => new ActivityCalculatedFieldBlock() ]) ];
        }

        return [ 'success' => false ];
    }

    /**
     * Сохранить парметры поля
     */
    public function actionSaveFieldData ()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $section = ActivityExtendedStatisticSectionsTemplates::getSection();

        return array_merge(ActivityExtendedStatisticFields::saveData(\Yii::$app->request->post()), [ 'html_fields' => $section->renderFields($this), 'section_id' => $section->id ]);
    }

    /**
     * Удаление формулы
     */
    public function actionDeleteFormulaField ()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $field = ActivityExtendedStatisticFieldsCalculated::find()->where([ 'id' => \Yii::$app->request->get('id') ])->one();
        $section = ActivityExtendedStatisticSectionsTemplates::getSection();

        if ($field && $section) {
            ActivityExtendedStatisticFieldsCalculated::deleteAll([ 'id' => $field->id ]);

            return [ 'success' => true, 'message' => 'Формула успешно удалена.', 'html_fields' => $section->renderFields($this), 'section_id' => $section->id ];
        }

        return [ 'success' => false, 'message' => 'Ошибка удаления формулы.' ];
    }

    public function actionBindActivityStatisticQuarter ()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [ 'success' => ActivityStatisticsPeriods::bindQuarters() ];
    }

    /**
     * Конфиг статистика
     * @return array
     */
    public function actionShowConfig ()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        return [ 'html' => $this->renderPartial('partials/_statistic-options', [ 'activity' => $this->getActivity() ]) ];
    }
}
