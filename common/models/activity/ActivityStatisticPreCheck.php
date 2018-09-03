<?php

namespace common\models\activity;

use common\models\dealers\Dealers;
use common\models\user\User;
use Yii;

/**
 * This is the model class for table "activity_statistic_pre_check".
 *
 * @property integer $id
 * @property integer $activity_id
 * @property integer $statistic_id
 * @property integer $dealer_id
 * @property integer $user_who_check
 * @property integer $quarter
 * @property integer $year
 * @property integer $is_checked
 * @property string $created_at
 * @property string $updated_at
 */
class ActivityStatisticPreCheck extends \yii\db\ActiveRecord
{
    const CHECK_STATUS_NONE = 0;
    const CHECK_STATUS_CHECKED = 1;
    const CHECK_STATUS_IN_PROGRESS = 2;
    const CHECK_STATUS_CANCEL = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_statistic_pre_check';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id', 'statistic_id', 'dealer_id', 'quarter', 'year', 'created_at', 'updated_at'], 'required'],
            [['activity_id', 'statistic_id', 'dealer_id', 'user_who_check', 'quarter', 'year', 'is_checked'], 'integer'],
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
            'activity_id' => 'Activity ID',
            'statistic_id' => 'Statistic ID',
            'dealer_id' => 'Dealer ID',
            'user_who_check' => 'User Who Check',
            'quarter' => 'Quarter',
            'year' => 'Year',
            'is_checked' => 'Is Checked',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getActivity() {
        return $this->hasOne(Activity::className(), ['id' => 'activity_id']);
    }

    public function getDealer() {
        return $this->hasOne(Dealers::className(), ['id' => 'dealer_id']);
    }

    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_who_check']);
    }

    /**
     * Получить статус проверки статистики
     * @return mixed
     */
    public function getCheckStatus() {
        $statuses = [
            self::CHECK_STATUS_NONE => '',
            self::CHECK_STATUS_CHECKED => 'Проверена',
            self::CHECK_STATUS_IN_PROGRESS => 'В процессе',
            self::CHECK_STATUS_CANCEL => 'Отменена',
        ];

        return $statuses[$this->is_checked];
    }

}
