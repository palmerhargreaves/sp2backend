<?php

namespace common\models\activity;

use Yii;

/**
 * This is the model class for table "mandatory_activity_quarters".
 *
 * @property integer $id
 * @property integer $activity_id
 * @property string $quarters
 * @property integer $year
 * @property string $created_at
 */
class MandatoryActivityQuarters extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mandatory_activity_quarters';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id', 'quarters', 'year', 'created_at'], 'required'],
            [['activity_id', 'year'], 'integer'],
            [['created_at'], 'safe'],
            [['quarters'], 'string', 'max' => 80],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_id' => 'Activity ID',
            'quarters' => 'Quarters',
            'year' => 'Year',
            'created_at' => 'Created At',
        ];
    }
}
