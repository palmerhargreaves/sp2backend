<?php

namespace common\models\activity;

use Yii;

/**
 * This is the model class for table "activity_special_agreement_users_list".
 *
 * @property integer $id
 * @property integer $activity_id
 * @property integer $user_id
 */
class ActivitySpecialAgreementUsersList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_special_agreement_users_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id', 'user_id'], 'required'],
            [['activity_id', 'user_id'], 'integer'],
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
            'user_id' => 'User ID',
        ];
    }
}
