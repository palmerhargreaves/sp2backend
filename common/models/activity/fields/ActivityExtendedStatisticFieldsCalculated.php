<?php

namespace common\models\activity\fields;

use Yii;

/**
 * This is the model class for table "activity_extended_statistic_fields_calculated".
 *
 * @property integer $id
 * @property integer $parent_field
 * @property integer $calc_field
 * @property string $calc_type
 * @property integer $activity_id
 */
class ActivityExtendedStatisticFieldsCalculated extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_extended_statistic_fields_calculated';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_field', 'calc_field', 'calc_type', 'activity_id'], 'required'],
            [['parent_field', 'calc_field', 'activity_id'], 'integer'],
            [['calc_type'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_field' => 'Parent Field',
            'calc_field' => 'Calc Field',
            'calc_type' => 'Calc Type',
            'activity_id' => 'ActivityController ID',
        ];
    }

    public function getCalcFieldName() {
        $field = ActivityExtendedStatisticFields::find()->where(['id' => $this->calc_field])->one();
        if ($field) {
            return $field->header;
        }

        return '';
    }
}
