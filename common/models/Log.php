<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $description
 * @property integer $object_id
 * @property integer $module_id
 * @property string $action
 * @property string $created_at
 * @property string $object_type
 * @property integer $importance
 * @property string $login
 * @property integer $dealer_id
 * @property string $title
 * @property string $icon
 * @property integer $message_id
 * @property integer $private_user_id
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'description', 'created_at', 'login'], 'required'],
            [['user_id', 'object_id', 'module_id', 'importance', 'dealer_id', 'message_id', 'private_user_id'], 'integer'],
            [['description'], 'string'],
            [['created_at'], 'safe'],
            [['action', 'object_type', 'login', 'title', 'icon'], 'string', 'max' => 255],
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
            'description' => 'Description',
            'object_id' => 'Object ID',
            'module_id' => 'Module ID',
            'action' => 'Action',
            'created_at' => 'Created At',
            'object_type' => 'Object Type',
            'importance' => 'Importance',
            'login' => 'Login',
            'dealer_id' => 'Dealer ID',
            'title' => 'Title',
            'icon' => 'Icon',
            'message_id' => 'Message ID',
            'private_user_id' => 'Private User ID',
        ];
    }
}
