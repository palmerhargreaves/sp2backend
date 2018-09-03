<?php

namespace common\models\activity;

use Yii;

/**
 * This is the model class for table "activity_type_company".
 *
 * @property integer $id
 * @property string $name
 * @property string $class_name
 * @property integer $percent
 */
class ActivityTypeCompany extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_type_company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'class_name'], 'required'],
            [['percent'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['class_name'], 'string', 'max' => 80],
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
            'class_name' => 'Class Name',
            'percent' => 'Percent',
        ];
    }
}
