<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 09.09.2018
 * Time: 18:09
 */

namespace common\models\activity\fields\blocks;


use common\models\activity\ActivityExtendedStatisticSections;

class BaseBlockModel extends ActivityExtendedStatisticSections
{
    protected $_block_template = 'partials/blocks/_field_form';
    protected $_block_fields_templates = 'partials/blocks/_fields_list';

    /**
     * @return ActivityCalculatedFieldBlock
     */
    protected function getCalculatedModel() {
        return new ActivityCalculatedFieldBlock();
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
            'html' => $view->renderAjax($this->_block_fields_templates, [
                'fields' => $this->getFieldsList(),
                'section' => $this,
                'calculated_model' => $this->getCalculatedModel(),
                'calculated_fields' => $this->getCalculatedFieldList()
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
                return $this->addFieldSuccess($view);
            }
        }

        return [ 'success' => false, 'message' => \Yii::t('app', 'Ошибка добавления нового поля.') ];
    }
}
