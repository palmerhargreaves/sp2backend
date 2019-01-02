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

    /**
     * Привязка списка кварталов к активности
     */
    public static function bindQuarters() {
        if (empty(Yii::$app->request->post('quarters'))) {
            return self::deleteAll(['year' => Yii::$app->request->post('year'), 'activity_id' => Yii::$app->request->post('id')]);
        }
        else {
            $item = self::findOne([ 'year' => Yii::$app->request->post('year'), 'activity_id' => Yii::$app->request->post('id') ]);
            if (!$item) {
                $item = new ActivityStatisticsPeriods();
            }

            $item->year = Yii::$app->request->post('year');
            $item->activity_id = Yii::$app->request->post('id');
            $item->quarters = Yii::$app->request->post('quarters');
        }

        return $item->save(false);
    }

    /**
     * @param $activity_id
     * @param $quarter
     * @param $year
     * @return int|string
     */
    public static function checkQuarter($activity_id, $quarter, $year) {
        return self::find()
            ->where(['activity_id' => $activity_id, 'year' => $year])
            ->andWhere(['like', 'quarters', '%'.$quarter.'%', false])->count();
    }
}
