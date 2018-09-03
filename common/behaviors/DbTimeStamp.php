<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 04.07.2017
 * Time: 11:12
 */

namespace common\behaviors;


use yii\base\Behavior;
use yii\db\ActiveRecord;

class DbTimeStamp extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate'
        ];
    }

    public function beforeInsert($event) {
        $model = $event->sender;
        if ($model->hasAttribute('created_at') && is_null($model->created_at)) {
            $model->created_at = new Expression('NOW()');
        }
        if ($model->hasAttribute('updated_at')) {
            $model->updated_at = new Expression('NOW()');
        }
    }

    public function beforeUpdate($event) {
        $model = $event->sender;
        if ($model->hasAttribute('updated_at')) {
            $model->updated_at = new Expression('NOW()');
        }
    }
}
