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
 * @property string $calcFieldName
 * @property mixed $calcFieldsNames
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
            [['parent_field', 'calc_field', 'activity_id', 'section_id'], 'integer'],
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

    /**
     * @param $calc_type
     * @return string
     */
    public static function getCalcTypeName($calc_type) {
        $calc_types = [
            'plus' => '+',
            'minus' => '-',
            'divide' => '/',
            'multiple' => '*',
            'percent' => '%'
        ];

        if (array_key_exists($calc_type, $calc_types)) {
            return $calc_types[$calc_type];
        }

        return '';
    }

    public function getCalcFieldName() {
        $field = ActivityExtendedStatisticFields::find()->where(['id' => $this->calc_field])->one();
        if ($field) {
            return $field->header;
        }

        return '';
    }

    public function getCalcFieldsNames() {
        $fields_names = [];

        $parent_field = ActivityExtendedStatisticFields::find()->where(['id' => $this->parent_field])->one();
        if ($parent_field) {
            $fields_names[] = $parent_field->header;
        }

        $calc_field = ActivityExtendedStatisticFields::find()->where(['id' => $this->calc_field])->one();
        if ($calc_field) {
            $fields_names[] = $calc_field->header;
        }

        return $this->calc_type != 'multiple' ? implode('&nbsp;<span class="task-cat teal" style="margin-left: 0px;">'.self::getCalcTypeName($this->calc_type).'</span>&nbsp;', $fields_names) : implode('', $fields_names);
    }
}
