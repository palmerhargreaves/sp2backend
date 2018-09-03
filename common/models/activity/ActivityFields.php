<?php

namespace common\models\activity;

use Yii;

/**
 * This is the model class for table "activity_fields".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $type
 * @property string $content
 * @property integer $activity_id
 * @property integer $req
 * @property integer $status
 * @property integer $parent_header_id
 * @property integer $group_id
 * @property integer $owner
 * @property integer $position
 * @property string $hash_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $quarter
 */
class ActivityFields extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_fields';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'type', 'content', 'activity_id', 'parent_header_id', 'group_id', 'owner', 'hash_id', 'created_at', 'updated_at'], 'required'],
            [['description', 'type', 'content', 'quarter'], 'string'],
            [['activity_id', 'req', 'status', 'parent_header_id', 'group_id', 'owner', 'position'], 'integer'],
            [['name', 'hash_id'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'type' => 'Type',
            'content' => 'Content',
            'activity_id' => 'Activity ID',
            'req' => 'Req',
            'status' => 'Status',
            'parent_header_id' => 'Parent Header ID',
            'group_id' => 'Group ID',
            'owner' => 'Owner',
            'position' => 'Position',
            'hash_id' => 'Hash ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'quarter' => 'Quarter',
        ];
    }

    public static function getFieldTypesList() {
        return [
            'string' => Yii::t('app', 'Строка'),
            'date' => Yii::t('app', 'Дата'),
            'number' => Yii::t('app', 'Число'),
            'file' => Yii::t('app', 'Файл'),
        ];
    }

    public static function getContentTypesList() {
        return [
            'price' => Yii::t('app', 'Сумма'),
            'counts' => Yii::t('app', 'Количество'),
            'other' => Yii::t('app', 'Другое'),
        ];
    }
}
