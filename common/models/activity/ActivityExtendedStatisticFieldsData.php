<?php

namespace common\models\activity;

use Yii;

/**
 * This is the model class for table "activity_extended_statistic_fields_data".
 *
 * @property integer $id
 * @property integer $field_id
 * @property integer $activity_id
 * @property integer $user_id
 * @property integer $dealer_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $value
 * @property integer $concept_id
 */
class ActivityExtendedStatisticFieldsData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_extended_statistic_fields_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['field_id', 'activity_id', 'user_id', 'dealer_id', 'created_at', 'updated_at', 'value'], 'required'],
            [['field_id', 'activity_id', 'user_id', 'dealer_id', 'concept_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['value'], 'string', 'max' => 80],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'field_id' => 'Field ID',
            'activity_id' => 'ActivityController ID',
            'user_id' => 'User ID',
            'dealer_id' => 'Dealer ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'value' => 'Value',
            'concept_id' => 'Concept ID',
        ];
    }
}
