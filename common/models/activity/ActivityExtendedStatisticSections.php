<?php

namespace common\models\activity;

use common\models\activity\fields\ActivityExtendedStatisticFields;
use common\models\activity\fields\ActivityExtendedStatisticFieldsCalculated;
use common\models\activity\sections\ActivitySectionInterface;
use common\models\activity\statistic\ActivitySettingsBlock;
use Yii;

/**
 * This is the model class for table "activity_extended_statistic_sections".
 *
 * @property integer $id
 * @property string $header
 * @property integer $parent_id
 * @property integer $status
 * @property integer $activity_id
 * @property string $graph_type
 */
class ActivityExtendedStatisticSections extends \yii\db\ActiveRecord implements ActivitySectionInterface
{
    protected $_template = '';

    protected $_block_template = '';
    protected $_block_fields_templates = '';

    protected $_fields_container = '#container-activity-statistic-fields-list';

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
            [['description', 'graph_type'], 'string'],
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
            'description' => 'Описание',
            'parent_id' => 'Parent ID',
            'status' => 'Status',
            'activity_id' => 'ActivityController ID',
            'graph_type' => 'Тип диаграммы'
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
        $html = $view->renderAjax('partials/blocks/_settings', [ 'section' => $this, 'model' => new ActivitySettingsBlock() ]);

        return $html;
    }

    public function renderFields($view) {
        // TODO: Implement getModel() method.
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

    public function addBlockField($view) {
        // TODO: Implement getModel() method.
    }

    /**
     * Получить список полей привязанных к блоку
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getFieldsList() {
        return ActivityExtendedStatisticFields::find()->where(['parent_id' => $this->id, 'activity_id' => $this->activity_id])->orderBy(['position' => SORT_ASC])->all();
    }

    /**
     * Получить список доступных формул для раздела
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getCalculatedFieldList() {
        return ActivityExtendedStatisticFields::find()->where(['parent_id' => $this->id, 'activity_id' => $this->activity_id, 'value_type' => ActivityExtendedStatisticFields::CALC])->orderBy(['position' => SORT_ASC])->all();
    }

    /**
     * Получить количестов привязанных полей к блоку
     * @return int|string
     */
    public function getFieldsCount() {
        return ActivityExtendedStatisticFields::find()
            ->where(['parent_id' => $this->id, 'activity_id' => $this->activity_id])
            ->andWhere(['!=', 'value_type', 'calc'])
            ->count();
    }

    /**
     * Получить список вычисляемых полей
     */
    public function getCalculatedFieldsCount() {
        return ActivityExtendedStatisticFields::find()
            ->where(['parent_id' => $this->id, 'activity_id' => $this->activity_id])
            ->andWhere(['=', 'value_type', 'calc'])
            ->count();
    }

    /**
     * Возврат результата добавления поля
     * @param $view
     * @return mixed
     */
    public function addFieldSuccess($view) {
        return array_merge([ 'success' => true,
            'message' => \Yii::t('app', 'Новое поле успешно добавлено.'),
            'section_id' => $this->id,
            'fields_count' => $this->getFieldsCount(),
            'calculated_fields_count' => $this->getCalculatedFieldsCount()
        ], $this->renderFields($view));
    }

    /**
     *
     */
    public function getGraphTypeImg() {
       $graph_imgs = [ 'waterfall' => 'img_funnel_sm.jpg', 'pie' => 'img_pie.jpg', 'linear' => '', 'bars' => '' ];

       return array_key_exists($this->graph_type, $graph_imgs) ? $graph_imgs[$this->graph_type] : 'none.png';
    }
}
