<?php

namespace common\models\logs;

use Yii;

/**
 * This is the model class for table "log_last_read".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $last_read
 */
class LogLastRead extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_last_read';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'last_read'], 'required'],
            [['user_id'], 'integer'],
            [['last_read'], 'safe'],
            [['user_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'last_read' => 'Last Read',
        ];
    }
}
