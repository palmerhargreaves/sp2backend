<?php

namespace common\models\agreement_model\statistic;


use common\models\agreement_model\AgreementModel;
use common\models\Log;
use common\utils\D;
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

    const DAY = 'day';
    const QUARTER = 'quarter';
    const MONTHS = 'months';
    const YEAR = 'year';

    public function __construct(Request $request = null)
    {
        $this->_request = $request;

        $current_year = $request && $request->post('year') ? $request->post('year') : date('Y');
        $this->_years_list = array_reverse(array_map(function($item) use($current_year) {
            $log_year = date('Y', strtotime($item['created_at']));

            return ['year' => $log_year, 'selected' => $current_year == $log_year];
        }, AgreementModel::find()->select(['created_at'])->groupBy(['year(created_at)'])->all()));

        $this->_quarters_list = $this->getQuartersListByYear($current_year);

        $this->_months_list = $this->getMonthsListByYear($current_year);
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
            return $this->getModelsCheckedList($selected_data, true);
        } else {
            //Выборка по году / кварталу / месяцу

            //Выборка по месяцу
            if (!empty($months)) {
                foreach ($months as $month) {
                    $date = sprintf('%s-%s', $year, $month < 10 ? '0'.$month : $month);

                    $result[$month] = $this->getModelsCheckedList($date, false);
                }

                return [ 'check_by' => self::MONTHS, 'data' => $result ];
            }
            //Выборка по кварталам
            else if (!empty($quarters)) {
                foreach ($quarters as $quarter) {
                    $quarter_months = !empty($months) ? $months : D::getQuarterMonths($quarter);

                    foreach ($quarter_months as $month) {
                        $date = sprintf('%s-%s', $year, $month < 10 ? '0'.$month : $month);

                        $result[$quarter][] = $this->getModelsCheckedList($date);
                    }
                }

                return [ 'check_by' => self::QUARTER, 'data' => $result ];
            }
            //Выборка по году
            else {
                $quarters = !empty($quarters) ? $quarters : $this->getQuartersListByYear($year);
                foreach ($quarters as $quarter) {
                    $quarter_months = !empty($months) ? $months : D::getQuarterMonths($quarter);

                    foreach ($quarter_months as $month) {
                        $date = sprintf('%s-%s', $year, $month < 10 ? '0'.$month : $month);

                        $result[$quarter][] = $this->getModelsCheckedList($date);
                    }
                }

                return [ 'check_by' => self::QUARTER, 'data' => $result ];

            }
        }
    }

    private function getModelsCheckedList($date, $only_by_day = false) {
        //ВЫборка данных по заявкам и отчетам
        $object_type_by_model = 'agreement_model';
        $model_actions = ['declined', 'accepted', 'declined_by_specialist', 'accepted_by_specialist'];

        $check_models_list = [ ];

        //Выборка завок проверенных только менеджеров
        $result_only_manager = $this->makeQuery($date, [], '', $object_type_by_model, $model_actions);

        $result_manager_designer_models_ids = [];
        foreach ($result_only_manager as $result_item) {
            //Обработка заявок только менеджером
            if ($result_item['work_type'] == self::MANAGER) {
                $item_date = date('Y-m-d', strtotime($result_item['created_at']));
                if (!array_key_exists($item_date, $check_models_list)) {
                    $check_models_list[$item_date] = [];
                }

                if (!in_array($result_item['object_id'], $check_models_list[$item_date])) {
                    $check_models_list[$item_date][] = $result_item['object_id'];
                }
            } else {
                $result_manager_designer_models_ids[$result_item['object_id']] = $result_item['object_id'];
            }
        }

        $result_manager_designer_models_ids = array_values($result_manager_designer_models_ids);
        if (!empty($result_manager_designer_models_ids)) {
            //Выборка заявок проверенных менеджером / дизайнером
            $result_manager_designer = $this->makeQuery($date, array_values($result_manager_designer_models_ids), '', $object_type_by_model, $model_actions);

            $check_manager_designer_result = [];
            foreach ($result_manager_designer as $result_item) {
                $item_date = date('Y-m-d', strtotime($result_item['created_at']));

                //Номер заявки
                if (!array_key_exists($result_item['object_id'], $check_manager_designer_result)) {
                    $check_manager_designer_result[$result_item['object_id']] = [];
                }

                //Дата проверки заявки
                if (!array_key_exists($item_date, $check_manager_designer_result[$result_item['object_id']])) {
                    $check_manager_designer_result[$result_item['object_id']][$item_date] = [];
                }

                //Действие выполненное при проверке
                if (!in_array($result_item['action'], $check_manager_designer_result[$result_item['object_id']][$item_date])) {
                    $check_manager_designer_result[$result_item['object_id']][$item_date][] = $result_item['action'];
                }
            }

            //Если выборка данных только за один день
            //Проверяем на полную проверку менеджера / дизайнера за выбранный день
            $check_models_list_by_manager_designer = [];
            if ($only_by_day) {
                foreach ($check_manager_designer_result as $object_id => $items) {
                    //Проверка менеджера
                    $manager_check = false;
                    if (in_array('accepted', $items[$date]) || in_array('declined', $items[$date])) {
                        $manager_check = true;
                    }

                    //Проверка дизайнера
                    $designer_check = false;
                    if (in_array('accepted_by_specialist', $items[$date]) || in_array('declined_by_specialist', $items[$date])) {
                        $designer_check = true;
                    }

                    //Если есть обе проверки от пользователей
                    if ($manager_check && $designer_check) {
                        if (!in_array($object_id, $check_models_list[$date])) {
                            $check_models_list_by_manager_designer[] = $object_id;
                        }
                    } else {
                        $model_check_status = [];

                        //Проверка не выполнена менеджером
                        if (!$manager_check) {
                            $model_check_status['manager'] = 'manager';
                        }

                        //Проверка не выполнена дизайнером
                        if (!$designer_check) {
                            $model_check_status['designer'] = 'designer';
                        }

                        //Если были проверки заявки до выбранной даты
                        foreach ($items as $date_key => $date_items) {
                            if ($date_key == $date) {
                                continue;
                            }

                            //Если есть история проверок
                            if (!empty($model_check_status)) {

                                //Проверка менеджера
                                if (isset($model_check_status['manager']) && in_array('accepted', $items[$date_key]) || in_array('declined', $items[$date_key])) {
                                    unset($model_check_status['manager']);
                                }

                                //Проверка дизайнера
                                if (isset($model_check_status['designer']) && in_array('accepted_by_specialist', $items[$date_key]) || in_array('declined_by_specialist', $items[$date_key])) {
                                    unset($model_check_status['designer']);
                                }

                                //Если была проверка менеджером или дизайнером заявки до выбранной даты, отмечаем заявку проверенной
                                if (empty($model_check_status)) {

                                    if (!in_array($object_id, $check_models_list[$date])) {
                                        $check_models_list_by_manager_designer[] = $object_id;
                                    }
                                }
                            }
                        }
                    }
                }

                $checked_reports_count = $this->getCheckedReportsCountByDate($date);

                return [
                    'check_by' => self::DAY,
                    'check_by_date' => $date,
                    'check_count' => !empty($check_models_list) ? count(array_values($check_models_list)[0]) : 0,
                    'check_count_by_manager_designer' => count($check_models_list_by_manager_designer),
                    'check_reports_count' => !empty($checked_reports_count) ? count(array_values($checked_reports_count)[0]) : 0
                ];
            } else {
                //Определяем выполнение есловий получение статуса Проверено, должна бьть проверка менеджером и дизайнером
                //Если условие не выполняется, учитываем для заявки статус выполнения (кем)
                //Проходим по всем записям пока условие выполнения проверки не будет корректным

                $model_check_status = [];
                foreach ($check_manager_designer_result as $object_id => $items) {
                    foreach ($items as $date_key => $date_items) {

                        //Проверка менеджера
                        $manager_check = false;
                        if (in_array('accepted', $date_items) || in_array('declined', $date_items)) {
                            $manager_check = true;
                        }

                        //Проверка дизайнера
                        $designer_check = false;
                        if (in_array('accepted_by_specialist', $date_items) || in_array('declined_by_specialist', $date_items)) {
                            $designer_check = true;
                        }

                        if (!array_key_exists($date_key, $check_models_list_by_manager_designer)) {
                            $check_models_list_by_manager_designer[$date_key] = [];
                        }

                        //Если есть история проверок
                        if (!empty($model_check_status)) {
                            //Если проверка не была выполнена менеджером
                            if (in_array('manager', $model_check_status) && $manager_check) {
                                $designer_check = true;
                            }

                            //Если проверка не была выполнена дизайнером
                            if (in_array('designer', $model_check_status) && $designer_check) {
                                $manager_check = true;
                            }

                            $model_check_status = [];
                        }

                        //Если есть обе проверки от пользователей
                        if ($manager_check && $designer_check) {
                            if (!in_array($object_id, $check_models_list_by_manager_designer[$date_key])) {
                                $check_models_list_by_manager_designer[$date_key][] = $object_id;
                            }
                        } else {
                            //Проверка не выполнена менеджером
                            if (!$manager_check) {
                                $model_check_status[] = 'manager';
                            }

                            //Проверка не выполнена дизайнером
                            if (!$designer_check) {
                                $model_check_status[] = 'designer';
                            }
                        }
                    }
                }

                $result_items = [];
                foreach ($check_models_list as $date_key => $items) {
                    $result_items[$date_key]['manager_check_count'] = count($items);
                }

                foreach ($check_models_list_by_manager_designer as $date_key => $items) {
                    $result_items[$date_key]['manager_designer_check_count'] = count($items);
                }

                $checked_reports_count = $this->getCheckedReportsCountByDate($date);

                foreach ($checked_reports_count as $date_key => $items) {
                    $result_items[$date_key]['reports_check_count'] = count($items);
                }

                return $result_items;
            }
        }

        return [];
    }

    /**
     * Полчить список проверенных отчетов за определенный период
     * @param $date
     * @return array
     */
    private function getCheckedReportsCountByDate($date) {
        $object_type_by_report = 'agreement_report';
        $report_actions = [ 'declined', 'accepted' ];

        $results = $this->makeQuery($date, [], '', $object_type_by_report, $report_actions);

        $check_models_list = [];
        foreach ($results as $result_item) {
            //Обработка заявок только менеджером
            $item_date = date('Y-m-d', strtotime($result_item['created_at']));
            if (!array_key_exists($item_date, $check_models_list)) {
                $check_models_list[$item_date] = [];
            }

            if (!in_array($result_item['object_id'], $check_models_list[$item_date])) {
                $check_models_list[$item_date][] = $result_item['object_id'];
            }
        }

        return $check_models_list;
    }

    /**
     * @param string $date
     * @param array|string $object_ids
     * @param string $check_type
     * @param string $object_type
     * @param array $actions
     * @return array|\yii\db\ActiveRecord[]
     */
    private function makeQuery($date = '', $object_ids = [], $check_type = '', $object_type = '', $actions = []) {
        $query = Log::find()
            ->select(['object_id', 'log.created_at', 'action', 'agreement_model.model_category_id', 'agreement_model_categories.work_type'])
            ->innerJoin('agreement_model', 'agreement_model.id = log.object_id')
            ->innerJoin('agreement_model_categories', 'agreement_model_categories.id = agreement_model.model_category_id')
            ->where(['object_type' => $object_type])
            ->andWhere(['IN', 'action', $actions])
            ->orderBy(['log.id' => SORT_ASC])
            ->asArray();

        //Выборка данных по дате
        if (!empty($date)) {
            $query->andWhere(['LIKE', 'log.created_at', '%'.$date.'%', false]);
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

    /**
     * Получить список месяцев по году
     * @param $year
     * @return array
     */
    private function getMonthsListByYear($year) {
        return array_map(function($item) {
            return date('n', strtotime($item['created_at']));
        },
            Log::find()->select(['created_at'])->where(['year(created_at)' => $year])->groupBy(['month(created_at)'])->all());
    }

    /**
     * Получить список доступных кварталов по году
     * @param $year
     * @return array
     */
    private function getQuartersListByYear($year) {
        return array_map(function($item) {
            return  D::getQuarter(date('Y-m-d', strtotime($item['created_at'])));
        },
            Log::find()->select(['created_at'])->where(['year(created_at)' => $year])->groupBy(['quarter(created_at)'])->all());
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
