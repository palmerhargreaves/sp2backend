<?php

namespace common\models\activity;

use common\models\activity\fields\ActivityExtendedStatisticFields;
use common\models\activity\steps\ActivityExtendedStatisticSteps;
use common\models\activity\traits\ActivityConfigsTrait;
use common\models\activity\traits\ActivityExportStatisticTrait;
use common\models\activity\traits\ActivityExtendedStatisticTrait;
use common\models\activity\traits\ActivityStatisticTrait;
use common\models\CustomRecord;
use Yii;

/**
 * This is the model class for table "activity".
 *
 * @property integer $id
 * @property string $name
 * @property string $start_date
 * @property string $end_date
 * @property string $description
 * @property string $efficiency_description
 * @property string $brief
 * @property integer $finished
 * @property integer $importance
 * @property string $created_at
 * @property string $updated_at
 * @property string $materials_url
 * @property integer $sort
 * @property string $custom_date
 * @property integer $has_concept
 * @property integer $many_concepts
 * @property integer $is_concept_complete
 * @property integer $hide
 * @property integer $select_activity
 * @property integer $is_limit_run
 * @property string $stats_description
 * @property integer $allow_to_all_dealers
 * @property string $stat_quarter
 * @property integer $stat_year
 * @property integer $position
 * @property integer $is_own
 * @property integer $allow_extended_statistic
 * @property integer $allow_certificate
 * @property integer $allow_share_name
 * @property integer $type_company_id
 * @property integer $own_activity
 * @property integer $required_activity
 * @property integer $mandatory_activity
 * @property integer allow_special_agreement
 * @property string $event_name
 */
class Activity extends CustomRecord
{
    use ActivityExtendedStatisticTrait;
    use ActivityStatisticTrait;
    use ActivityConfigsTrait;
    use ActivityExportStatisticTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'start_date', 'end_date', 'efficiency_description', 'created_at', 'updated_at', 'stats_description', 'stat_quarter'], 'required'],
            [['start_date', 'end_date', 'created_at', 'updated_at'], 'safe'],
            [['description', 'efficiency_description', 'brief', 'custom_date', 'stats_description'], 'string'],
            [['finished', 'importance', 'sort', 'has_concept', 'many_concepts', 'is_concept_complete', 'hide', 'select_activity', 'is_limit_run', 'allow_to_all_dealers', 'stat_year', 'position', 'is_own', 'allow_extended_statistic', 'allow_certificate', 'allow_share_name', 'type_company_id', 'own_activity', 'required_activity', 'mandatory_activity', 'allow_special_agreement'], 'integer'],
            [['name', 'materials_url', 'event_name'], 'string', 'max' => 255],
            [['stat_quarter'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'description' => 'Description',
            'efficiency_description' => 'Efficiency Description',
            'brief' => 'Brief',
            'finished' => 'Finished',
            'importance' => 'Importance',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'materials_url' => 'Materials Url',
            'sort' => 'Sort',
            'custom_date' => 'Custom Date',
            'has_concept' => 'Has Concept',
            'many_concepts' => 'Many Concepts',
            'is_concept_complete' => 'Is Concept Complete',
            'hide' => 'Hide',
            'select_activity' => 'Select ActivityController',
            'is_limit_run' => 'Is Limit Run',
            'stats_description' => 'Stats Description',
            'allow_to_all_dealers' => 'Allow To All Dealers',
            'stat_quarter' => 'Stat Quarter',
            'stat_year' => 'Stat Year',
            'position' => 'Position',
            'is_own' => 'Is Own',
            'allow_extended_statistic' => 'Allow Extended Statistic',
            'allow_certificate' => 'Allow Certificate',
            'allow_special_agreement' => 'Активность со спец. согласованием',
            'allow_share_name' => 'Allow Share Name',
            'type_company_id' => 'Type Company ID',
            'own_activity' => 'Own ActivityController',
            'required_activity' => 'Required ActivityController',
            'mandatory_activity' => 'Mandatory ActivityController',
            'event_name' => 'Event Name',
        ];
    }

    /**
     * @param bool $count
     * @return array|int|string|\yii\db\ActiveRecord[]
     */
    public function getSteps($count = false) {
        if ($count) {
            return ActivityExtendedStatisticSteps::find()->where(['activity_id' => $this->id])->count();
        }

        return ActivityExtendedStatisticSteps::find()->where(['activity_id' => $this->id])->orderBy(['position' => SORT_ASC])->all();
    }

    /**
     * @param bool $count
     * @return array|int|string|\yii\db\ActiveRecord[]
     */
    public function getFields($count = false) {
        if ($count) {
            return ActivityExtendedStatisticFields::find()->where(['activity_id' => $this->id])->count();
        }

        return ActivityExtendedStatisticFields::find()->where(['activity_id' => $this->id])->all();
    }

    public function getFieldsSections() {
        return ActivityExtendedStatisticSections::find()->where(['activity_id' => $this->id])->orderBy(['position' => SORT_ASC])->all();
    }

    public function getCompany() {
        return $this->hasOne(ActivityTypeCompany::className(), ['id' => 'type_company_id']);
    }

    public function getNameAndNumber() {
        return sprintf('[%s] %s', $this->id, $this->name);
    }

    /**
     * Получить статисику привязанную к активности
     */
    public function getActivityVideoStatistic() {
        return ActivityVideoRecordsStatistics::find()->where(['activity_id' => $this->id])->one();
    }
}
