<?php

namespace common\models\activity;

use Yii;

/**
 * This is the model class for table "activity_task".
 *
 * @property integer $id
 * @property string $name
 * @property integer $activity_id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $is_concept_complete
 * @property integer $position
 */
class ActivityTask extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'activity_id', 'created_at', 'updated_at'], 'required'],
            [['activity_id', 'is_concept_complete', 'position'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
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
            'activity_id' => 'Activity ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_concept_complete' => 'Is Concept Complete',
            'position' => 'Position',
        ];
    }
}
