<?php

namespace common\models\activity\fields;

use Yii;

/**
 * This is the model class for table "activity_extended_statistic_sections".
 *
 * @property integer $id
 * @property string $header
 * @property integer $parent_id
 * @property integer $status
 * @property array|\yii\db\ActiveRecord[] $fieldsList
 * @property integer $activity_id
 */
class ActivityExtendedStatisticSections extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_extended_statistic_sections';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['header', 'parent_id', 'activity_id'], 'required'],
            [['parent_id', 'status', 'activity_id'], 'integer'],
            [['header'], 'string', 'max' => 255],
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
            'parent_id' => 'Parent ID',
            'status' => 'Status',
            'activity_id' => 'ActivityController ID',
        ];
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getFields() {
        return ActivityExtendedStatisticFields::find()->where(['activity_id' => $this->activity_id, 'parent_id' => $this->id])->orderBy(['position' => SORT_ASC])->all();
    }
}
