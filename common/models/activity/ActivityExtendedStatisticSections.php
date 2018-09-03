<?php

namespace common\models\activity;

use Yii;

/**
 * This is the model class for table "activity_extended_statistic_sections".
 *
 * @property integer $id
 * @property string $header
 * @property integer $parent_id
 * @property integer $status
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
            [['parent_id', 'status', 'activity_id', 'position'], 'integer'],
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

    public static function makeSort() {
        $activity_id = Yii::$app->request->post("activity");
        $sections = Yii::$app->request->post("sections");

        $position = 1;
        foreach ($sections as $section) {
            $section_item = ActivityExtendedStatisticSections::find()->where([ 'id' => $section, 'activity_id' => $activity_id ])->one();
            if ($section_item) {
                $section_item->position = $position;
                $section_item->save(false);

                $position++;
            }
        }

        return [ $position > 1 ? true : false ];
    }
}
