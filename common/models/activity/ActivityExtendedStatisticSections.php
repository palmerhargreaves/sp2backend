<?php

namespace common\models\activity;

use common\models\activity\sections\ActivitySectionInterface;
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
class ActivityExtendedStatisticSections extends \yii\db\ActiveRecord implements ActivitySectionInterface
{
    protected $_template = '';

    protected $_block_template = '';

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
            [['parent_id', 'status', 'activity_id', 'position', 'section_template_id'], 'integer'],
            [['header'], 'string', 'max' => 255],
            [['description'], 'string'],
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
            'description' => 'Description',
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

    /**
     * Render field list of section
     * @param $view
     * @return mixed
     */
    public function render($view)
    {
        $html = $view->renderPartial('partials/blocks/_settings', [ 'section' => $this ]);

        return $html;
    }

    /**
     * Получить данные по блоку
     * @param $activity
     * @param $section_template_id
     * @return mixed
     */
    public function getSection($activity, $section_template_id) {
        return self::find()->where(['activity_id' => $activity->id, 'section_template_id' => $section_template_id])->one();
    }

    /**
     * Отключаем секцию
     * @return bool
     */
    public static function disableSection() {
        $section = self::findOne(['id' => Yii::$app->request->post('section_id')]);

        if ($section) {
            $section->status = 0;
            return $section->save(false);
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        // TODO: Implement getModel() method.
    }
}
