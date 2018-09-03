<?php

namespace common\models\activity;

use Yii;

/**
 * This is the model class for table "activity_fields_values".
 *
 * @property integer $id
 * @property integer $field_id
 * @property integer $dealer_id
 * @property string $val
 * @property integer $q
 * @property integer $year
 * @property string $created_at
 * @property string $updated_at
 */
class ActivityFieldsValues extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_fields_values';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['field_id', 'dealer_id', 'val', 'created_at', 'updated_at'], 'required'],
            [['field_id', 'dealer_id', 'q', 'year'], 'integer'],
            [['created_at'], 'safe'],
            [['val'], 'string', 'max' => 80],
            [['updated_at'], 'string', 'max' => 30],
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
            'dealer_id' => 'Dealer ID',
            'val' => 'Val',
            'q' => 'Q',
            'year' => 'Year',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
