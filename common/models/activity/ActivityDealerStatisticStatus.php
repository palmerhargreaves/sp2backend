<?php

namespace common\models\activity;

use Yii;

/**
 * This is the model class for table "activity_dealer_statistic_status".
 *
 * @property integer $id
 * @property integer $dealer_id
 * @property integer $activity_id
 * @property string $stat_type
 * @property integer $q1
 * @property integer $q2
 * @property integer $q3
 * @property integer $q4
 * @property integer $concept_id
 * @property integer $year
 * @property string $created_at
 * @property string $updated_at
 * @property integer $complete
 * @property integer $always_open
 * @property integer $ignore_statistic
 * @property integer $ignore_q1_statistic
 * @property integer $ignore_q2_statistic
 * @property integer $ignore_q3_statistic
 * @property integer $ignore_q4_statistic
 */
class ActivityDealerStatisticStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_dealer_statistic_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dealer_id', 'activity_id', 'stat_type', 'created_at', 'updated_at'], 'required'],
            [['dealer_id', 'activity_id', 'q1', 'q2', 'q3', 'q4', 'concept_id', 'year', 'complete', 'always_open', 'ignore_statistic', 'ignore_q1_statistic', 'ignore_q2_statistic', 'ignore_q3_statistic', 'ignore_q4_statistic', 'using_steps'], 'integer'],
            [['stat_type'], 'string'],
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
            'dealer_id' => 'Dealer ID',
            'activity_id' => 'Activity ID',
            'stat_type' => 'Stat Type',
            'q1' => 'Q1',
            'q2' => 'Q2',
            'q3' => 'Q3',
            'q4' => 'Q4',
            'concept_id' => 'Concept ID',
            'year' => 'Year',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'complete' => 'Complete',
            'always_open' => 'Always Open',
            'ignore_statistic' => 'Ignore Statistic',
            'ignore_q1_statistic' => 'Ignore Q1 Statistic',
            'ignore_q2_statistic' => 'Ignore Q2 Statistic',
            'ignore_q3_statistic' => 'Ignore Q3 Statistic',
            'ignore_q4_statistic' => 'Ignore Q4 Statistic',
        ];
    }

    /**
     * Получить данные о количестве заполненных данных по статистике в разрезе квартала
     * @param $activityId
     * @param $quarter
     * @return int|string
     */
    public static function getActivityStatisticData($activityId, $quarter) {
        return self::find()
            ->where(['activity_id' => $activityId])
            ->andWhere(['year' => date('Y')])
            ->andWhere(['q'.$quarter => $quarter])
            //->andWhere(['ignore_q'.$quarter.'_statistic' => 0])
            //->andWhere(['complete' => true])
            ->andWhere(['using_steps' => true])
            ->count();
    }
}
