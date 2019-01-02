<?php

namespace common\models\activity;

use common\models\activity\sections\ActivitySectionTargets;

/**
 * This is the model class for table "activity_extended_statistic_sections_templates".
 *
 * @property integer $id
 * @property string $name
 * @property string $cls_name
 */
class ActivityExtendedStatisticSectionsTemplates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_extended_statistic_sections_templates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'cls_name'], 'required'],
            [['name', 'cls_name'], 'string', 'max' => 255],
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
            'cls_name' => 'Cls Name',
        ];
    }

    /**
     * Создаем список секций с шаблонов в БД
     * @param Activity $activity
     * @return array
     */
    public static function getList(Activity $activity) {
        $sections_templates_list = self::find()->all();
        $result = [];

        $sections_ordered_list = [];
        $position = 0;
        foreach ($sections_templates_list as $section_template) {
            $cls_name = self::getSectionTemplateCls($section_template);

            $section = $cls_name::getSection($activity, $section_template->id);
            $sections_ordered_list[$section ? ($section->position != 0 ? $section->position : $position++) : $position++][] = [ 'section_template' => $section_template, 'activity' => $activity, 'section' => $section ];
        }

        ksort($sections_ordered_list);
        foreach ($sections_ordered_list as $item) {
            $result[] = isset($item[0]) ? $item[0] : null;
        }

        return array_filter($result);
    }

    /**
     * Получить данные по блоку шаблона
     * @param $id
     * @param Activity $activity
     * @return array
     */
    public static function getBlock($id, Activity $activity) {
        $block_item = self::findOne(['id' => $id]);

        $cls_name = self::getSectionTemplateCls($block_item);

        return [ 'section_template' => $block_item, 'activity' => $activity, 'section' => $cls_name::getSection($activity, $block_item->id) ];
    }

    /**
     * Активируем блок статистики
     */
    public static function activateSection() {
        $activity_id = \Yii::$app->request->post('id');
        $section_template_id = \Yii::$app->request->post('section_template_id');

        $template_cls = self::getSectionTemplateCls(self::findOne(['id' => $section_template_id]));

        if (!($section = $template_cls::findOne(['activity_id' => $activity_id, 'section_template_id' => $section_template_id]))) {
            $section = new $template_cls();
            $section->activity_id = $activity_id;
            $section->section_template_id = $section_template_id;
            $section->status = 1;
        } else {
            $section->status = 1;
        }
        $section->save(false);

        return $section;
    }

    /**
     * Получаем блок по индексу, получаем шаблон, создаем класс на основе полученного шаблона и отрисовавем данные
     */
    public static function getSection() {
        $section = ActivityExtendedStatisticSections::findOne(['id' => \Yii::$app->request->post('section_id')]);
        $template_cls = self::getSectionTemplateCls(self::findOne(['id' => $section->section_template_id]));

        return $template_cls::findOne(['id' => \Yii::$app->request->post('section_id')]) ;
    }

    private static function getSectionTemplateCls($template) {
        $cls_name = 'common\models\activity\sections\ActivitySection'.implode('', array_map(function($item) {
                return ucfirst($item);
            }, explode('_', $template->cls_name)));

        return $cls_name;
    }


}
