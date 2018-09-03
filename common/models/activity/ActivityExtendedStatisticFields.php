<?php

namespace common\models\activity;

use Yii;

/**
 * This is the model class for table "activity_extended_statistic_fields".
 *
 * @property integer $id
 * @property string $header
 * @property string $value_type
 * @property integer $activity_id
 * @property integer $parent_id
 * @property integer $status
 * @property string $description
 * @property integer $position
 * @property integer $required
 */
class ActivityExtendedStatisticFields extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_extended_statistic_fields';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['header', 'activity_id', 'description'], 'required'],
            [['value_type'], 'string'],
            [['activity_id', 'parent_id', 'status', 'position', 'required'], 'integer'],
            [['header', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'header' => 'Header',
            'value_type' => 'Value Type',
            'activity_id' => 'Activity ID',
            'parent_id' => 'Parent ID',
            'status' => 'Status',
            'description' => 'Description',
            'position' => 'Position',
            'required' => 'Required',
        ];
    }
}
