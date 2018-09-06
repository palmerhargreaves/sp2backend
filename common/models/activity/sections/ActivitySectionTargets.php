<?php

namespace common\models\activity\sections;

use common\models\activity\ActivityExtendedStatisticSections;
use common\models\activity\fields\ActivityExtendedStatisticFields;
use common\models\activity\fields\blocks\ActivityTargetBlock;

/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 03.09.2018
 * Time: 11:48
 *
 * @property mixed $model
 */

class ActivitySectionTargets extends ActivityExtendedStatisticSections {
    protected $_block_template = 'partials/blocks/_targets';

    public function beforeSave($insert)
    {
        $this->header = is_null($this->header) ? 'Цели' : $this->header;

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * @return mixed
     */
    public function getModel() {
        return new ActivityTargetBlock();
    }

    public function render($view)
    {
        $html = parent::render($view);
        $html .= $view->renderAjax($this->_block_template, [ 'section' => $this, 'model' => $this->getModel() ]);

        return $html;
    }

    /**
     * Добавление поля
     */
    public function addBlockField($view) {
        $activity_id = \Yii::$app->request->post('activity_id');

        $model = $this->getModel();
        if ($model->load(\Yii::$app->request->post())) {
            $model->parent_id = $this->id;
            $model->activity_id = $activity_id;
            $model->value_type = 'dig';
            $model->status = 1;

            if (ActivityExtendedStatisticFields::findOne(['activity_id' => $model->activity_id, 'parent_id' => $model->parent_id, 'dealers_group' => $model->dealers_group])) {
                return [ 'success' => false, 'message' => \Yii::t('app', 'Ошибка добавления нового поля. Для выбранной группы дилеров уже создано поле.') ];
            }

            //Добавляем новое поле и получаем список всех полей привязанных к блоку
            if ($model->save()) {
                return array_merge([ 'success' => true,
                    'message' => \Yii::t('app', 'Новое поле успешно добавлено.'),
                ], $this->renderFields($view));
            }
        }

        return [ 'success' => false, 'message' => \Yii::t('app', 'Ошибка добавления нового поля.') ];
    }

    public function renderFields($view) {
        return [
            'html_container' => '#container-activity-statistic-fields-list',
            'html' => $view->renderAjax('partials/blocks/_targets_fields_list', [ 'fields' => $this->getFieldsList(), 'section' => $this ])
        ];
    }
}
