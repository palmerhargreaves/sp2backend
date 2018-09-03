<?php

namespace common\models\model;

use Yii;

/**
 * This is the model class for table "agreement_model_type".
 *
 * @property integer $id
 * @property integer $parent_category_id
 * @property string $name
 * @property string $identifier
 * @property string $agreement_type
 * @property string $report_field_description
 * @property string $field_description
 * @property integer $concept
 * @property integer $is_photo_report
 * @property integer $position
 * @property integer $status
 */
class AgreementModelType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agreement_model_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_category_id', 'concept', 'is_photo_report', 'position', 'status'], 'integer'],
            [['name', 'identifier', 'field_description'], 'required'],
            [['agreement_type'], 'string'],
            [['name', 'identifier', 'report_field_description', 'field_description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_category_id' => 'Parent Category ID',
            'name' => 'Name',
            'identifier' => 'Identifier',
            'agreement_type' => 'Agreement Type',
            'report_field_description' => 'Report Field Description',
            'field_description' => 'Field Description',
            'concept' => 'Concept',
            'is_photo_report' => 'Is Photo Report',
            'position' => 'Position',
            'status' => 'Status',
        ];
    }
}
