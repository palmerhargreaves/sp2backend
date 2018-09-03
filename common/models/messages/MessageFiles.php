<?php

namespace common\models\messages;

use Yii;

/**
 * This is the model class for table "message_file".
 *
 * @property integer $id
 * @property integer $message_id
 * @property string $file
 * @property string $path
 * @property integer $editor
 * @property string $created_at
 */
class MessageFiles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message_file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message_id', 'file', 'created_at'], 'required'],
            [['message_id', 'editor'], 'integer'],
            [['created_at'], 'safe'],
            [['file', 'path'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'message_id' => 'Message ID',
            'file' => 'File',
            'path' => 'Path',
            'editor' => 'Editor',
            'created_at' => 'Created At',
        ];
    }
}
