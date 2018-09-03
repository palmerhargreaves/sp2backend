<?php

namespace common\models\agreement_model;

use Yii;

/**
 * This is the model class for table "agreement_model_field".
 *
 * @property integer $id
 * @property integer $model_type_id
 * @property integer $parent_category_id
 * @property integer $field_parent_id
 * @property string $name
 * @property string $identifier
 * @property string $type
 * @property integer $sort
 * @property string $list
 * @property string $units
 * @property string $format_hint
 * @property string $format_expression
 * @property integer $required
 * @property string $right_format
 * @property integer $child_field
 * @property integer $hide
 * @property string $def_value
 * @property integer $editable
 * @property integer $position
 */
class AgreementModelField extends \yii\db\ActiveRecord
{
    const IDENTIFIER_PERIOD_FIELD = 'period';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agreement_model_field';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_type_id', 'name', 'identifier', 'type', 'list', 'def_value'], 'required'],
            [['model_type_id', 'parent_category_id', 'field_parent_id', 'sort', 'required', 'child_field', 'hide', 'editable', 'position'], 'integer'],
            [['type'], 'string'],
            [['name', 'identifier', 'list', 'units', 'format_hint', 'format_expression', 'right_format'], 'string', 'max' => 255],
            [['def_value'], 'string', 'max' => 80],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model_type_id' => 'Model Type ID',
            'parent_category_id' => 'Parent Category ID',
            'field_parent_id' => 'Field Parent ID',
            'name' => 'Name',
            'identifier' => 'Identifier',
            'type' => 'Type',
            'sort' => 'Sort',
            'list' => 'List',
            'units' => 'Units',
            'format_hint' => 'Format Hint',
            'format_expression' => 'Format Expression',
            'required' => 'Required',
            'right_format' => 'Right Format',
            'child_field' => 'Child Field',
            'hide' => 'Hide',
            'def_value' => 'Def Value',
            'editable' => 'Editable',
            'position' => 'Position',
        ];
    }

    public function isPeriodField() {
        return $this->type == self::IDENTIFIER_PERIOD_FIELD;
    }
}
