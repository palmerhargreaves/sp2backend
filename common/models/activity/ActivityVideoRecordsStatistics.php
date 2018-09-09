<?php

namespace common\models\activity;

use Yii;

/**
 * This is the model class for table "activity_video_records_statistics".
 *
 * @property integer $id
 * @property string $header
 * @property integer $activity_id
 * @property integer $status
 */
class ActivityVideoRecordsStatistics extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_video_records_statistics';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['header', 'activity_id'], 'required'],
            [['activity_id', 'status', 'not_using_importer', 'allow_statistic_pre_check'], 'integer'],
            [['header'], 'string', 'max' => 255],
            [['last_updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'header' => 'Название',
            'activity_id' => 'Активность',
            'status' => 'Статус',
            'last_updated_at' => 'Дата обновления'
        ];
    }
}
