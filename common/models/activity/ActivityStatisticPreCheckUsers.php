<?php

namespace common\models\activity;

use Yii;

/**
 * This is the model class for table "activity_statistic_pre_check_users".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $activity_id
 */
class ActivityStatisticPreCheckUsers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_statistic_pre_check_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'activity_id'], 'required'],
            [['user_id', 'activity_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'activity_id' => 'Activity ID',
        ];
    }
}
