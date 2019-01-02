<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 22.11.2017
 * Time: 18:27
 */

namespace common\models\activity\traits;


use common\models\activity\ActivityDealerStatisticStatus;
use common\models\activity\ActivityVideoRecordsStatistics;
use common\models\agreement_model\AgreementModel;

trait ActivityStatisticTrait
{
    private static $_total_models_count_in_month = null;

    public function getModelsCount($date = null) {
        return self::getModelsByDate($this->id, $date);
    }

    public function getDealersCount() {
        return AgreementModel::find()->where(['activity_id' => $this->id])->groupBy(['dealer_id'])->count();
    }

    /**
     * @return string
     */
    public function getActiveStats() {
        $result = [];

        for ($month = 1; $month <= 12; $month++) {
            $result[] = $this->getModelsCount(date('Y-m', strtotime(date('Y').'-'.$month)));
        }

        return implode(':', $result);
    }

    public static function getModelsByDate($activity_id, $date = null, $by_activity = true) {
        $query = AgreementModel::find();

        //Фильтр по активности
        if ($by_activity) {
            $query->where(['activity_id' => $activity_id]);
        }

        //Фильтр по дате
        if (!is_null($date)) {
            $query->andWhere(['LIKE', 'created_at', $date]);
        }

        return $query->count();
    }

    public static function getActivityPercentByMonth($activity_id) {
        if (is_null(self::$_total_models_count_in_month)) {
            self::$_total_models_count_in_month = self::getModelsByDate($activity_id,  date('Y-m'),false);
        }

        return self::$_total_models_count_in_month > 0
            ? round(self::getModelsByDate($activity_id, date('Y-m')) * 100 / self::$_total_models_count_in_month, 1)
            : 0;
    }

    /**
     * Получить данные по статистике в разрезе кварталов
     */
    public function getActivityStatisticCompleteByQ() {
        $result = [];

        for ($q = 1; $q <= 4; $q++) {
            $result[] = ActivityDealerStatisticStatus::getActivityStatisticData($this->id, $q);
        }

        return implode(":", $result);
    }

    /**
     * Сохранение параметров статистики
     * @return array
     */
    public static function saveStatisticConfig() {
        $activity_statistic = ActivityVideoRecordsStatistics::find()->where(['activity_id' => \Yii::$app->request->post('activity')])->one();

        if (!$activity_statistic) {
            $activity_statistic = new ActivityVideoRecordsStatistics();
            $activity_statistic->header = sprintf('Статистика по активности (%d)', \Yii::$app->request->post('activity'));
            $activity_statistic->activity_id = \Yii::$app->request->post('activity');
        }

        if ($activity_statistic) {
            $fields = \Yii::$app->request->post('fields');

            foreach ($fields as $field) {
                $activity_statistic->{$field['field']} = $field['val'];
            }
            $activity_statistic->last_updated_at = date('Y-m-d H:i:s');
            $activity_statistic->save(false);

            return [ "success" => true ];
        }

        return [ "success" => false ];
    }
}