<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 22.11.2017
 * Time: 19:07
 */

namespace common\models\activity\traits;


use common\models\activity\ActivityModelsTypesNecessarily;
use common\models\activity\ActivityStatisticsPeriods;
use common\models\activity\ActivityTask;
use common\models\activity\ActivityTypeCompany;
use common\models\activity\ActivityVideoRecordsStatistics;

trait ActivityConfigsTrait
{
    public function getConfigs() {
        return  [
            'company_type' => [
                'active' => $this->type_company_id != 0 ? true : false,
                'label' => \Yii::t('app', 'Кампания')
            ],
            'required_models' => [
                'active' => ActivityModelsTypesNecessarily::find()->where(['activity_id' => $this->id])->count() > 0,
                'label' => \Yii::t('app', 'Заявки')
            ],
            'tasks' => [
                'active' => ActivityTask::find()->where(['activity_id' => $this->id])->count() > 0,
                'label' => \Yii::t('app', 'Задачи')
            ],
            'statistics_video' => [
                'active' => ActivityVideoRecordsStatistics::find()->where(['activity_id' => $this->id])->count() > 0,
                'label' => \Yii::t('app', 'Стат.(видео)')
            ],
            'periods' => [
                'active' => ActivityStatisticsPeriods::find()->where(['activity_id' => $this->id])->count() > 0,
                'label' => \Yii::t('app', 'Период')
            ],
        ];
    }

    /**
     * Получить
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getCompanyList() {
        return ActivityTypeCompany::find()->all();
    }

    public function getRequiredModelsTypesList() {

    }

    public function getTasks() {

    }

    public function getStatsByVideo() {

    }

    public function getStats() {

    }
}