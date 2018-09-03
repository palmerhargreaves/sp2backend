<?php

namespace common\models\activity;

use common\models\model\AgreementModelType;
use Yii;

/**
 * This is the model class for table "activity_models_types_necessarily".
 *
 * @property integer $id
 * @property integer $activity_id
 * @property integer $model_type_id
 * @property integer $activity_task_id
 * @property integer $is_used
 */
class ActivityModelsTypesNecessarily extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_models_types_necessarily';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id', 'model_type_id', 'activity_task_id'], 'required'],
            [['activity_id', 'model_type_id', 'activity_task_id', 'is_used'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_id' => 'Activity ID',
            'model_type_id' => 'Model Type ID',
            'activity_task_id' => 'Activity Task ID',
            'is_used' => 'Is Used',
        ];
    }

    public function getType() {
        return $this->hasOne(AgreementModelType::className(), ['id' => 'model_type_id']);
    }

    public function getTask() {
        return $this->hasOne(ActivityTask::className(), ['id' => 'activity_task_id']);
    }
}
