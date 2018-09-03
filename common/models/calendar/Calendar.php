<?php

namespace common\models\calendar;

use Yii;

/**
 * This is the model class for table "calendar".
 *
 * @property integer $id
 * @property string $title
 * @property string $start_date
 * @property string $end_date
 * @property string $created_at
 * @property string $updated_at
 */
class Calendar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'calendar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'start_date', 'end_date', 'created_at', 'updated_at'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['start_date', 'end_date', 'created_at', 'updated_at'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
