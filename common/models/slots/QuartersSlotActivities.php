<?php

namespace common\models\slots;

use Yii;

/**
 * This is the model class for table "quarters_slot_activities".
 *
 * @property integer $id
 * @property integer $slot_id
 * @property integer $activity_id
 */
class QuartersSlotActivities extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'quarters_slot_activities';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['slot_id', 'activity_id'], 'required'],
            [['slot_id', 'activity_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slot_id' => 'Slot ID',
            'activity_id' => 'Activity ID',
        ];
    }
}
