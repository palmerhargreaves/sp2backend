<?php

namespace common\models\activity;

use Yii;

/**
 * This is the model class for table "activity_video_records_statistics_headers".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $header
 * @property integer $position
 */
class ActivityVideoRecordsStatisticsHeaders extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_video_records_statistics_headers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'header'], 'required'],
            [['parent_id', 'position'], 'integer'],
            [['header'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'header' => 'Header',
            'position' => 'Position',
        ];
    }
}
