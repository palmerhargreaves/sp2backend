<?php

namespace common\models\logs;

use Yii;

/**
 * This is the model class for table "log_entry_read".
 *
 * @property integer $id
 * @property integer $entry_id
 * @property integer $user_id
 */
class LogEntryRead extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log_entry_read';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entry_id', 'user_id'], 'required'],
            [['entry_id', 'user_id'], 'integer'],
            [['user_id', 'entry_id'], 'unique', 'targetAttribute' => ['user_id', 'entry_id'], 'message' => 'The combination of Entry ID and User ID has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entry_id' => 'Entry ID',
            'user_id' => 'User ID',
        ];
    }
}
