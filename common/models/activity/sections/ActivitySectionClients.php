<?php

namespace common\models\activity\sections;

use common\models\activity\ActivityExtendedStatisticSections;
use common\models\activity\fields\blocks\ActivityClientsBlock;
use common\models\activity\fields\blocks\ActivityClientsFormulaBlock;

/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 03.09.2018
 * Time: 11:48
 *
 * @property mixed $model
 */

class ActivitySectionClients extends ActivityExtendedStatisticSections {

    protected $_block_template = 'partials/blocks/clients/_clients';

    public function beforeSave($insert)
    {
        $this->header = is_null($this->header) ? 'Клиенты' : $this->header;

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * @return mixed
     */
    public function getModel() {
        return new ActivityClientsBlock();
    }

    /**
     * @return ActivityClientsFormulaBlock
     */
    private function getFormulaModel() {
        return new ActivityClientsFormulaBlock();
    }

    public function render($view)
    {
        $html = parent::render($view);
        $html .= $view->renderAjax($this->_block_template, [ 'section' => $this, 'model' => $this->getModel() ]);

        return $html;
    }

    public function renderFields($view) {
        return [
            'html_container' => $this->_fields_container,
            'html' => $view->renderAjax('partials/blocks/clients/_clients_fields_list', [
                'fields' => $this->getFieldsList(),
                'section' => $this,
                'formula_model' => $this->getFormulaModel(),
                'formulas' => $this->getFormulasList()
            ])
        ];
    }

    public function addBlockField($view) {
        $activity_id = \Yii::$app->request->post('activity_id');

        $model = $this->getModel();
        if ($model->load(\Yii::$app->request->post())) {
            $model->parent_id = $this->id;
            $model->activity_id = $activity_id;
            $model->value_type = 'dig';
            $model->status = 1;

            //Добавляем новое поле и получаем список всех полей привязанных к блоку
            if ($model->save()) {
                return array_merge([ 'success' => true,
                    'message' => \Yii::t('app', 'Новое поле успешно добавлено.'),
                    'section_id' => $this->id
                ], $this->renderFields($view));
            }
        }

        return [ 'success' => false, 'message' => \Yii::t('app', 'Ошибка добавления нового поля.') ];
    }
}
