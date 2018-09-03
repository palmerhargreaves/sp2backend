<?php

namespace common\models\activity\steps;

use Yii;

/**
 * This is the model class for table "activity_extended_statistic_step_emails".
 *
 * @property integer $id
 * @property integer $step_id
 * @property integer $user_id
 * @property string $created_at
 */
class ActivityExtendedStatisticStepEmails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_extended_statistic_step_emails';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['step_id', 'user_id', 'created_at'], 'required'],
            [['step_id', 'user_id'], 'integer'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'step_id' => 'Step ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
        ];
    }
}
