<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 22.11.2017
 * Time: 22:49
 */

namespace common\models\activity\utils;

use common\models\agreement_model\AgreementModel;

class ActivitiesStatistics
{
    const STATS_BY_MONTH = "month";

    const MODEL_COMPLETED = 'accepted';
    const MODEL_IN_WAIT = 'in_wait';
    const MODEL_DECLINED = 'declined';
    const MODEL_IN_WAIT_SPECIALIST = 'in_wait_specialist';

    public static function getStatisticsByActivities($type = "year") {
        return $type == self::STATS_BY_MONTH ? self::getDataByMonth() : self::getDataByYear();
    }

    public static function getDataByMonth() {

    }

    public static function getDataByYear() {
        $result = [];
        $labels = [];

        for($month = 1; $month <= 12; $month++) {
            $labels[] = $month;

            $result_items = self::getData("Y-m", "Y", $month);
            $result[$month]["completed"] = $result_items["completed"];
            $result[$month]["in_wait"] = $result_items["in_wait"];
            $result[$month]["declined"] = $result_items["declined"];
            $result[$month]["with_reports"] = $result_items["with_reports"];
            $result[$month]["date"] = date("Y-m", strtotime(date('Y-'.$month)));
        }

        return ["labels" => $labels, "data" => $result];
    }

    public static function getData($dateFormat, $dateSubFormat, $value) {
        $result = ["completed" => 0, "in_wait" => 0, "declined" => 0, "with_reports" => 0];

        $models = AgreementModel::find()
            ->select(["agreement_model.status am_status", "report_id", "agreement_model_report.status"])
            ->leftJoin('agreement_model_report', 'agreement_model_report.model_id = agreement_model.id')
            ->where(['LIKE', 'agreement_model.created_at', date($dateFormat, strtotime(date($dateSubFormat.'-'.$value)))])
            ->all();

        foreach ($models as $model) {
            if ($model->am_status == self::MODEL_COMPLETED && !is_null($model->report_id) && $model->status == self::MODEL_COMPLETED) {
                $result["completed"]++;
            } else if ($model->am_status == self::MODEL_IN_WAIT || $model->am_status == self::MODEL_IN_WAIT_SPECIALIST ) {
                $result["in_wait"]++;
            } else if ($model->am_status == self::MODEL_DECLINED) {
                $result["declined"]++;
            } else if (!is_null($model['report_id'])) {
                $result["with_reports"]++;
            } else {
                $result["in_wait"]++;
            }
        }

        return $result;
    }
}