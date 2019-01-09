<?php

namespace common\models\agreement_model\statistic;


use common\models\agreement_model\AgreementModel;
use common\models\Log;
use common\utils\Utils;
use yii\web\Request;

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
    private $_request = null;

    const MANAGER = 'manager';
    const MANAGER_DESIGNER = 'manager_designer';

    public function __construct(Request $request = null)
    {
        $this->_request = $request;

        $current_year = date('Y');
        $this->_years_list = array_reverse(array_map(function($item) use($current_year) {
            $log_year = date('Y', strtotime($item['created_at']));

            return ['year' => $log_year, 'selected' => $current_year == $log_year];
        }, AgreementModel::find()->select(['created_at'])->groupBy(['year(created_at)'])->all()));

        $this->_quarters_list = range(1, 4);

        $this->_months_list = Utils::getQuartersMonths();
    }

    /**
     * Фильтр количества заявок
     */
    public function filterData() {
        list($selected_data, $year, $quarters, $months) = array(
            str_replace('/', '-', $this->_request->post('selected_data')),
            $this->_request->post('year'),
            $this->_request->post('quarters'),
            $this->_request->post('months')
            );

        //Выборка данных по заявкам только по выюранной дате
        if (!empty($selected_data)) {
            $this->getModelsCheckedList($selected_data);
        } else {
            //Выборка по году / кварталу / месяцу

        }
    }

    private function getModelsCheckedList($date) {
        $check_models_list = [ ];

        //Выборка завок проверенных только менеджеров
        $result_only_manager = $this->makeQuery($date);
        $result_manager_designer_models_ids = [];
        foreach ($result_only_manager as $result_item) {
            //Отбработка заявок олько менеджером
            if ($result_item['work_type'] == self::MANAGER) {
                if (!in_array($result_item['object_id'], $check_models_list)) {
                    $check_models_list[] = $result_item['object_id'];
                }
            } else {
                $result_manager_designer_models_ids[$result_item['object_id']] = $result_item['object_id'];
            }
        }

        //Выборка заявок проверенных менеджером / дизайнером
        var_dump(array_values($result_manager_designer_models_ids));
        exit;
        $result_manager_designer = $this->makeQuery('', array_values($result_manager_designer_models_ids));

        foreach ($result_only_manager as $result_item) {

        }
    }

    /**
     * @param string $date
     * @param array|string $object_ids
     * @param string $check_type
     * @return array|\yii\db\ActiveRecord[]
     */
    private function makeQuery($date = '', $object_ids = [], $check_type = '') {
        $query = Log::find()
            ->select(['object_id', 'log.created_at', 'action', 'agreement_model.model_category_id', 'agreement_model_categories.work_type'])
            ->innerJoin('agreement_model', 'agreement_model.id = log.object_id')
            ->innerJoin('agreement_model_categories', 'agreement_model_categories.id = agreement_model.model_category_id')
            ->where(['object_type' => 'agreement_model'])
            ->andWhere(['IN', 'action', ['declined', 'accepted', 'declined_by_specialist', 'accepted_by_specialist']])
            ->orderBy(['log.id' => SORT_DESC])
            ->asArray();

        //Выборка данных по дате
        if (!empty($date)) {
            $query->andWhere(['LIKE', 'log.created_at', $date.'%', false]);
        }

        //Выборка данных по индексам заявок
        if (!empty($object_ids)) {
            $query->andWhere(['IN', 'object_id', $object_ids]);
        }

        //Проверка заявки (только менеджер или менеджер / дизайнер)
        if (!empty($check_type)) {
            $query->andWhere(['agreement_model_categories.work_type' => $check_type]);
        }

        return $query->all();
    }

    private function getReportsCheckedList($date) {

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
