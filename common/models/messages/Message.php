<?php

namespace common\models\messages;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property integer $id
 * @property integer $discussion_id
 * @property integer $user_id
 * @property string $user_name
 * @property string $text
 * @property string $created_at
 * @property integer $system
 * @property integer $private_user_id
 * @property integer $mark_as_read
 * @property integer $msg_show
 * @property string $msg_status
 * @property integer $reply_on_message_id
 * @property string $who_get_message
 * @property string $who_get_message_ids
 * @property integer $contact_id
 * @property string $msg_type
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['discussion_id', 'user_name'], 'required'],
            [['discussion_id', 'user_id', 'system', 'private_user_id', 'mark_as_read', 'msg_show', 'reply_on_message_id', 'contact_id'], 'integer'],
            [['text', 'msg_status', 'who_get_message', 'msg_type'], 'string'],
            [['created_at'], 'safe'],
            [['user_name'], 'string', 'max' => 255],
            [['who_get_message_ids'], 'string', 'max' => 80],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'discussion_id' => 'Discussion ID',
            'user_id' => 'User ID',
            'user_name' => 'User Name',
            'text' => 'Text',
            'created_at' => 'Created At',
            'system' => 'System',
            'private_user_id' => 'Private User ID',
            'mark_as_read' => 'Mark As Read',
            'msg_show' => 'Msg Show',
            'msg_status' => 'Msg Status',
            'reply_on_message_id' => 'Reply On Message ID',
            'who_get_message' => 'Who Get Message',
            'who_get_message_ids' => 'Who Get Message Ids',
            'contact_id' => 'Контакт',
            'msg_type' => 'Тип сообщения',
        ];
    }
}
