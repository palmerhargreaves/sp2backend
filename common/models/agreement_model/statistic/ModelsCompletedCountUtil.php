<?php

namespace common\models\agreement_model\statistic;


use common\models\agreement_model\AgreementModel;
use common\utils\Utils;

/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 08.01.2019
 * Time: 11:49
 */

class ModelsCompletedCountUtil {
    private $_years_list = [];
    private $_months_list = [];
    private $_quarters_list = [];

    public function __construct()
    {
        $current_year = date('Y');
        $this->_years_list = array_reverse(array_map(function($item) use($current_year) {
            $log_year = date('Y', strtotime($item['created_at']));

            return ['year' => $log_year, 'selected' => $current_year == $log_year];
        }, AgreementModel::find()->select(['created_at'])->groupBy(['year(created_at)'])->all()));

        $this->_quarters_list = range(1, 4);

        $this->_months_list = Utils::getQuartersMonths();
    }

    /**
     * Получить список годов по созданным заявкам
     * @return array
     */
    public function getYearsList() {
        return $this->_years_list;
    }

    /**
     * Кварталы года
     */
    public function getQuartersList() {
        return $this->_quarters_list;
    }

    /**
     *  Список месяцев
     */
    public function getMonthsList() {
        return $this->_months_list;
    }
}
