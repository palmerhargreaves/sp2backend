<?php

namespace common\models\activity\steps;

use Yii;

/**
 * This is the model class for table "activity_extended_statistic_step_values".
 *
 * @property integer $id
 * @property integer $step_id
 * @property integer $activity_id
 * @property integer $dealer_id
 * @property integer $field_id
 * @property string $value
 * @property string $created_at
 */
class ActivityExtendedStatisticStepValues extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_extended_statistic_step_values';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['step_id', 'activity_id', 'dealer_id', 'field_id', 'value', 'created_at'], 'required'],
            [['step_id', 'activity_id', 'dealer_id', 'field_id'], 'integer'],
            [['created_at'], 'safe'],
            [['value'], 'string', 'max' => 80],
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
            'activity_id' => 'ActivityController ID',
            'dealer_id' => 'Dealer ID',
            'field_id' => 'Field ID',
            'value' => 'Value',
            'created_at' => 'Created At',
        ];
    }

    public function getStep() {
        return $this->hasOne(ActivityExtendedStatisticSteps::className(), ['id' => 'step_id']);
    }
}
