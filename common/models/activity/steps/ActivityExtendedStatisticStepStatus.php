<?php

namespace common\models\activity\steps;

use Yii;

/**
 * This is the model class for table "activity_extended_statistic_step_status".
 *
 * @property integer $id
 * @property integer $step_id
 * @property integer $activity_id
 * @property integer $dealer_id
 * @property integer $year
 * @property integer $quarter
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class ActivityExtendedStatisticStepStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_extended_statistic_step_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['step_id', 'activity_id', 'dealer_id', 'year', 'quarter', 'created_at', 'updated_at'], 'required'],
            [['step_id', 'activity_id', 'dealer_id', 'year', 'quarter', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
            'year' => 'Year',
            'quarter' => 'Quarter',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
