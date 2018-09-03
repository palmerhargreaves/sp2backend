<?php

namespace common\models\activity;

use Yii;

/**
 * This is the model class for table "activity_statistics_periods".
 *
 * @property integer $id
 * @property integer $year
 * @property string $quarters
 * @property integer $activity_id
 */
class ActivityStatisticsPeriods extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_statistics_periods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['year', 'quarters', 'activity_id'], 'required'],
            [['year', 'activity_id'], 'integer'],
            [['quarters'], 'string', 'max' => 80],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'year' => 'Year',
            'quarters' => 'Quarters',
            'activity_id' => 'Activity ID',
        ];
    }
}
