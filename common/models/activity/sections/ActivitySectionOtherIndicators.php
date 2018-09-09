<?php

namespace common\models\activity\sections;

use common\models\activity\fields\blocks\ActivityOtherIndicatorsBlock;
use common\models\activity\fields\blocks\BaseBlockModel;

/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 03.09.2018
 * Time: 11:48
 */

class ActivitySectionOtherIndicators extends BaseBlockModel {
    protected $_block_template = 'partials/blocks/other_indicators/_add_fields_form';
    protected $_block_fields_templates = 'partials/blocks/other_indicators/_fields_list';

    public function beforeSave($insert)
    {
        $this->header = is_null($this->header) ? 'Прочие показатели' : $this->header;

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function getModel ()
    {
        return new ActivityOtherIndicatorsBlock();
    }

    public function addBlockField($view) {
        $activity_id = \Yii::$app->request->post('activity_id');

        $model = $this->getModel();
        if ($model->load(\Yii::$app->request->post())) {
            $model->parent_id = $this->id;
            $model->activity_id = $activity_id;
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

    public function renderFields($view) {
        return [
            'html_container' => $this->_fields_container,
            'html' => $view->renderAjax('partials/blocks/other_indicators/_fields_list', [ 'fields' => $this->getFieldsList(), 'section' => $this ])
        ];
    }
}
