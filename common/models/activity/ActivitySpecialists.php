<?php

namespace common\models\activity;

use Yii;

/**
 * This is the model class for table "activity_specialists".
 *
 * @property integer $id
 * @property integer $activity_id
 * @property integer $user_id
 */
class ActivitySpecialists extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_specialists';
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

    /**
     * ДПривязка / удаление пользователя к активности
     */
    public static function bindData() {
        $bind_data = (boolean)Yii::$app->request->post('bind_data');
        $activity_id = Yii::$app->request->post('activity_id');
        $user_id = Yii::$app->request->post('user_id');

        if ($bind_data) {
            $new_item = new ActivitySpecialists();
            $new_item->activity_id = $activity_id;
            $new_item->user_id = $user_id;
            $new_item->save(false);
        } else {
            ActivitySpecialists::deleteAll(['activity_id' => $activity_id, 'user_id' => $user_id]);
        }
    }
}
