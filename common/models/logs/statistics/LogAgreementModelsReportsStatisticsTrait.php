<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 30.11.2017
 * Time: 16:34
 */

namespace common\models\logs\statistics;


use common\models\logs\Log;
use common\models\user\User;
use common\utils\D;

trait LogAgreementModelsReportsStatisticsTrait
{
    /**
     * Получить данные по согласованию модели
     * @param $modelId
     * @return array
     */
    public static function getModelAgreementTimeLine($modelId)
    {
        $dealersIds = User::getUsersIdsByGroup(User::USER_GROUP_DEALER);

        $labels = [
            'add' => \Yii::t('app', 'Новая заявка'),
            'edit' => \Yii::t('app', 'Отправлено на согласование'),
            'accepted' => \Yii::t('app', 'Согласовано'),
            'declined_by_specialist' => \Yii::t('app', 'Отклонено специалистом'),
            'accepted_by_specialist' => \Yii::t('app', 'Согаласовано специалистом'),
            'declined' => \Yii::t('app', 'Отклонено'),
        ];

        $logs = self::find()
            ->select("log.id, log.object_id, log.created_at, log.action, log.description, log.login, log.user_id, model_category_id")
            ->joinWith("models")
            ->where(['object_id' => $modelId])
            ->andWhere(['log.private_user_id' => 0])
            //->andWhere([ 'in', 'action', [ 'sent_to_specialist', 'accepted_by_specialist', 'edit', 'declined', 'accepted', 'declined_by_specialist' ] ])
            ->andWhere(['not in', 'log.action', self::EXCLUDED_ACTIONS])
            ->orderBy(['log.id' => SORT_ASC, 'log.object_id' => SORT_ASC])
            ->all();

        $timeline_result = [];
        $dates = $dates_list = [];
        $agreement_actions_count = [];

        $position = 1;
        foreach ($logs as $log_item) {
            $date = date('Y-m-d', strtotime($log_item->created_at));

            if (!in_array($date, $dates) && !in_array($log_item->action, self::EXCLUDED_ACTIONS)) {
                $dates[] = $date;

                if (!array_key_exists($log_item->object_id, $dates_list)) {
                    $dates_list[$log_item->object_id] = [];
                }

                if (!in_array($date, $dates_list[$log_item->object_id])) {
                    $dates_list[$log_item->object_id][] = $date;
                }
            }

            if (!array_key_exists($date, $timeline_result)) {
                $timeline_result[$date] = ['left' => [], 'right' => []];
            }

            if (!array_key_exists($log_item->action, $agreement_actions_count)) {
                $agreement_actions_count[$log_item->action] = 0;
            }

            $agreement_actions_count[$log_item->action]++;

            if (in_array($log_item->user_id, $dealersIds)) {
                $timeline_result[$date]['left'][] = ['item' => $log_item, 'position' => $position++];
            } else {
                $timeline_result[$date]['right'][] = ['item' => $log_item, 'position' => $position++];
            }
        }

        //Получаем количество дней до полного согласования
        $days_count = [];
        foreach ($dates_list as $object_id => $dates_items) {
            if (!array_key_exists($object_id, $days_count)) {
                $days_count[$object_id] = 0;
            }

            $days_count[$object_id] = self::calcDaysCount($dates_items) + self::calcDaysCount($dates_items, false);
        }

        //Количество дней выполнения заявки
        if (array_key_exists($object_id, $days_count)) {
            $agreement_days_count = $days_count[$modelId];
        }

        return [
            'timeline' => $timeline_result,
            'agreement_days_count' => $agreement_days_count > 0 ? $agreement_days_count : 1,
            'chart_data' => [
                'labels' => array_map(function ($label) use ($labels) {
                    return array_key_exists($label, $labels) ? $labels[$label] : $label;
                }, array_keys($agreement_actions_count)),
                'values' => array_values($agreement_actions_count)
            ]];
    }

    /**
     * Получить данные по статистике в разрезе часов
     * @param int $max_work_hours
     * @param bool $by_designer
     * @return array
     */
    public static function getModelsReportsDataByHours($max_work_hours = 0, $by_designer = false)
    {
        $result = [];
        $result_for_chart = [];

        $start_from = self::getStartDate();
        $end_from = self::getEndDate();

        $object_type = !empty(\Yii::$app->request->post("filter_object_type")) ? \Yii::$app->request->post("filter_object_type") : self::OBJECT_TYPE_MODEL;
        if ($object_type == self::OBJECT_TYPE_REPORT) {
            $object_type = [self::OBJECT_TYPE_REPORT, self::OBJECT_TYPE_REPORT_WITH_CONCEPT];
        } else {
            $object_type = [$object_type];
        }

        $filter_model_type = !empty(\Yii::$app->request->post("filter_model_type")) ? \Yii::$app->request->post("filter_model_type") : "";

        $result_count = [];
        $models_full_agreement_list = [];

        $diff = strtotime($end_from) - strtotime($start_from);
        $diff_days = floor($diff / (60 * 60 * 24));

        $dates_list = [];
        $dates_list_with_hours = [];

        $models_agreement_steps = [];
        //Начальная дата
        $calc_date = date('Y-m-d', strtotime($start_from));
        for ($day = 1; $day <= $diff_days; $day++) {

            $query = self::makeQuery($object_type, $filter_model_type);
            $query->andWhere(['like', 'log.created_at', $calc_date]);

            //Проверка по специалисту
            if ($by_designer) {
                $query->andWhere(['in', 'log.action', ['sent_to_specialist', 'accepted_by_specialist', 'declined_by_specialist']]);
            }

            $items_list = $query->all();
            foreach ($items_list as $item) {
                $item_date = date('Y-m-d', strtotime($item->created_at));
                //Если выходной, проходим дальше без учета этого дня
                /*if (!D::workDay($item_date)) {
                    continue;
                }*/

                //Сохраняем для вывода полной информации о согласовании модели (количество потраченных дней)
                $models_full_agreement_list[] = $item['object_id'];

                //Сохраняем пред. список дат
                if (!array_key_exists($item->object_id, $dates_list)) {
                    $dates_list[$item->object_id] = [];
                    $dates_list_with_hours[$item->object_id][$item_date] = [];
                }

                if (!in_array($item->object_id, $dates_list[$item->object_id])) {
                    $dates_list[$item->object_id][] = $item_date;
                }

                if (!array_key_exists($item['object_id'], $models_agreement_steps)) {
                    $models_agreement_steps[$item['object_id']] = 0;
                }

                if ($by_designer) {
                    if ($item->action == 'sent_to_specialist') {
                        $models_agreement_steps[$item['object_id']]++;
                    }
                } else {
                    if ($item->action == 'add' || $item->action == 'edit') {
                        $models_agreement_steps[$item['object_id']]++;
                    }
                }

                $result[$item['object_id']][$models_agreement_steps[$item['object_id']]][$item_date][$item->action] = [
                    'status' => self::getModelStatusLabel($item["action"], $item),
                    'action' => $item->action,
                    'user_id' => $item->user_id,
                    'login' => $item->login,
                    'description' => $item->description,
                    'original_date' => $item->created_at
                ];
            }

            $calc_date = $model_date = date('Y-m-d', strtotime('+ ' . $day . ' days', strtotime($start_from)));
        }

        //Проходим по полученному результату и получаем количество шагов при согласовании заявки
        //Каждый шаг, приваязан к событию - add или edit
        $models_result = [];
        $models_agreement_steps = [];
        $models_exists_in_steps = [];

        foreach ($result as $model_id => $agreements_steps) {
            foreach ($agreements_steps as $agreement_step_ind => $result_item_date) {
                foreach ($result_item_date as $item_date => $item_actions) {
                    foreach ($item_actions as $action => $action_data) {
                        //Добавляем шаг согласования
                        //С учетом дизайнера
                        if ($by_designer) {
                            if ($action == 'sent_to_specialist') {
                                $models_agreement_steps[$model_id][] = $action;
                                $models_exists_in_steps[$model_id][count($models_agreement_steps[$model_id]) - 1] = [];
                            }
                        } else {
                            if ($action == 'add' || $action == 'edit') {
                                $models_agreement_steps[$model_id][] = $action;
                                $models_exists_in_steps[$model_id][count($models_agreement_steps[$model_id]) - 1] = [];
                            }
                        }

                        //Записываем информацию по согласованию
                        if (array_key_exists($model_id, $models_agreement_steps)) {
                            //Если в каждом шаге есть уже данные от пользователя то, ничего не записываем в рез. набор
                            if (!in_array($action_data['user_id'], $models_exists_in_steps[$model_id][count($models_agreement_steps[$model_id]) - 1])) {
                                //Сохраняем данные в набор
                                $models_exists_in_steps[$model_id][count($models_agreement_steps[$model_id]) - 1][] = $action_data['user_id'];

                                //Если нет записи в результирующем наборе, добавляем
                                if (!array_key_exists($model_id, $models_result)) {
                                    $models_result[ $model_id ] = [];
                                }

                                if (!isset($models_result[ $model_id ][ count($models_agreement_steps[ $model_id ]) - 1 ])) {
                                    $models_result[ $model_id ][ count($models_agreement_steps[ $model_id ]) - 1 ] = [];
                                }

                                if (!in_array($action, $models_result[ $model_id ][ count($models_agreement_steps[ $model_id ]) - 1 ])) {
                                    $models_result[ $model_id ][ count($models_agreement_steps[ $model_id ]) - 1 ][ $action ] = [ 'date' => $item_date, 'data' => $action_data ];
                                }
                            }
                        }
                    }
                }
            }
        }

        //Проходим по полученным шагам и опредеяем время проверки заявки (согласование, отклонение)
        $models_result_with_steps = [];
        $result_for_chart_days = [];
        $model_colors = [];

        $min_hour = time() * 2;
        $max_hour = 0;

        $min_hours = 0;
        $max_hours = 0;

        foreach ($models_result as $model_id => $steps) {
            //Проходим по шагам и получаем начальную дату шага и конечную
            foreach ($steps as $step_key => $step_actions_list) {
                $step_data_list = [];

                $step_data_list[] = array_shift($step_actions_list);
                $step_data_list[] = array_pop($step_actions_list);

                $step_data_list = array_filter($step_data_list);

                //Проверяем, если есть первое действие
                if (isset($step_data_list[0])) {
                    //Проверяем на второе действие
                    //Без второго действия заявку не фиксируем в конечном результате
                    if (count($step_data_list) > 1) {
                        if (!array_key_exists($model_id, $models_result_with_steps)) {
                            $models_result_with_steps[$model_id] = [];

                            if (count($steps) > 1) {
                                srand();
                                $model_colors[$model_id] = sprintf('%s,%s,%s,0.5', mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
                            }
                        }

                        //$days = floor((strtotime($end_date) - strtotime($start_date)) / (60*60*24));

                        $hours = $max_work_hours != 0
                            ? self::calcFullHoursBeetwenDates([$step_data_list[0]['data']['original_date'], $step_data_list[count($step_data_list) - 1]['data']['original_date']], $model_id)
                            : self::calcHoursSum([$step_data_list[0]['data']['original_date'], $step_data_list[count($step_data_list) - 1]['data']['original_date']], $model_id);

                        if ($hours < 0) {
                            continue;
                        }

                        //Дополнительная проверка на максимальное кличество рабочих часов потраченных на проверку (для каждого шага)
                        //Если больше чем установлено, переходим к сл. шагу
                        if ($hours < $max_work_hours && $max_work_hours != 0) {
                            continue;
                        }

                        //Суммируем общее количество дней по заявке
                        //Как рабочих так и выходных, если с заявкой велась работа в выходные
                        //$hours = $hours == 0 ? 1 : $hours;
                        //Минимальная дата в наборе
                        if ($hours < $min_hour) {
                            $min_hours = $hours;
                        }

                        //Максимальная дата в наборе
                        if ($hours > $max_hour) {
                            $max_hours = $hours;
                        }

                        //var_dump($hours, $model_id);
                        $last_action = $step_data_list[count($step_data_list) - 1];

                        //Заполняем для диаграммы количество заявок выполненных за определенное количество дней
                        if (!isset($result_for_chart_days[$hours])) {
                            $result_for_chart_days[$hours] = 1;

                            $result_for_chart[sprintf("%s ча%s", $hours, \common\utils\NumbersHelper::numberEnd($hours, array('с', 'са', 'сов')))] = 0;
                        }

                        //Заполняем количество заявко, с которыми работали менеджеры / дизайнеры
                        if (!isset($result_count[$hours]["count"])) {
                            $result_count[$hours]["count"] = 0;
                        }

                        if ($last_action['data']['action'] != 'add' && $last_action['data']['action'] != 'edit') {
                            $result_count[$hours]["count"]++;
                            $result_for_chart[sprintf("%s ча%s", $hours, \common\utils\NumbersHelper::numberEnd($hours, array('с', 'са', 'сов')))] += 1;
                        }

                        $models_result_with_steps[$model_id][$step_key] = [
                            'days' => $hours,
                            'color' => isset($model_colors[$model_id]) ? $model_colors[$model_id] : '',
                            'steps_action' => [
                                'first' => $step_data_list[0],
                                'last' => $last_action
                            ]
                        ];
                    }
                }
            }
        }
        unset($models_result);

        /*var_dump($models_result);*/
        /*var_dump($models_result_with_steps);
        exit;*/

        ksort($result_for_chart);
        ksort($result_count);

        return [
            'models_list' => $models_result_with_steps,
            'chart_data' => $result_for_chart,
            'count' => $result_count,
            'min_days' => $min_hours,
            'max_days' => $max_hours
        ];
    }

    /**
     * @param $models_full_agreement_list
     * @return array
     */
    public static function getFullAgreementModelsPeriod($models_full_agreement_list, $object_type, $filter_model_type)
    {
        //Проходим по полученным шагам и опредеяем время проверки заявки (согласование, отклонение)
        $models_result_with_steps = [];
        $result_for_chart_days = [];
        $models_result_by_days = [];

        $models_count_by_days = [];
        $models_count_by_days_list = [];

        foreach ($models_full_agreement_list as $model_id) {
            $query = self::makeQuery(array_merge($object_type, [self::OBJECT_TYPE_REPORT, self::OBJECT_TYPE_REPORT_WITH_CONCEPT]), $filter_model_type);
            $query->andWhere(['object_id' => $model_id]);

            $model_logs = $query->all();
            $model_dates = [];
            $model_actions = [];

            foreach ($model_logs as $model_log) {
                $model_date = date('Y-m-d', strtotime($model_log->created_at));

                //Сохраняем пред. список дат
                if (!in_array($model_date, $model_dates)) {
                    $model_dates[] = $model_date;
                }

                $model_actions[] = $model_log;
            }

            //Делаем проход по периоду дат от старта и до последней, убираем выходные дни
            $work_days = self::calcDaysCount($model_dates) + self::calcDaysCount($model_dates, false);

            //Получаем общее количество потраченных дней на проверку заявки
            $work_days = $work_days != 0 ? $work_days : 1;

            //Учиваем общее количество заявок по дням
            if (!in_array($model_id, $models_count_by_days_list)) {
                if (!array_key_exists($work_days, $models_count_by_days)) {
                    $models_count_by_days[$work_days] = 1;
                } else {
                    $models_count_by_days[$work_days]++;
                }

                $models_count_by_days_list[] = $model_id;
            }

            $first_item = array_shift($model_actions);
            $last_item = array_pop($model_actions);

            $first_action = [];
            if ($first_item) {
                $first_action = ['status' => self::getModelStatusLabel($first_item["action"], $first_item), 'action' => $first_item->action, 'user_id' => $first_item->user_id, 'login' => $first_item->login, 'description' => $first_item->description];
            }

            $last_action = [];
            if ($last_item) {
                $last_action = ['status' => self::getModelStatusLabel($last_item["action"], $last_item), 'action' => $last_item->action, 'user_id' => $last_item->user_id, 'login' => $last_item->login, 'description' => $last_item->description];
            }

            if (!array_key_exists($model_id, $models_result_by_days)) {
                $models_result_by_days[$model_id] = [
                    'days' => $work_days,
                    'first_action' => ['data' => $first_action],
                    'last_action' => ['data' => $last_action]
                ];
            }
        }

        //Заполняем данные для список и графиков
        foreach ($models_result_by_days as $model_id => $model_data) {
            //Количество дней потраченных на выполнение заявки
            $days = $model_data['days'];

            //Заполняем для диаграммы количество заявок выполненных за определенное количество дней
            if (!isset($result_for_chart_days[$days])) {
                $result_for_chart_days[$days] = $models_count_by_days[$days];

                $result_for_chart[sprintf("%s д%s", $days, \common\utils\NumbersHelper::numberEnd($days, array('ень', 'ня', 'ней')))] = $models_count_by_days[$days];
            }

            //Заполняем количество заявко, с которыми работали менеджеры / дизайнеры
            if (!isset($result_count[$days]["count"])) {
                $result_count[$days]["count"] = $models_count_by_days[$days];
            }

            $models_result_with_steps[$model_id][1] = [
                'days' => $days,
                'color' => '',
                'steps_action' => [
                    'first' => $model_data['first_action'],
                    'last' => $model_data['last_action']
                ]
            ];
        }

        unset($models_result);

        ksort($result_for_chart);
        ksort($models_count_by_days);

        return [
            'models_list' => $models_result_with_steps,
            'chart_data' => $result_for_chart,
            'count' => $result_count,
            'min_days' => 1,
            'max_days' => max($models_count_by_days),
            'models_count_by_days' => $models_count_by_days
        ];
    }

    /**
     * @param $object_type
     * @param $filter_model_type
     * @param bool $is_full_agreement_period_type
     * @return mixed
     */
    private static function makeQuery($object_type, $filter_model_type, $is_full_agreement_period_type = false)
    {
        $query = self::find()
            ->select("log.id, log.object_id, log.created_at, log.action, log.description, log.login, log.user_id, model_category_id")
            ->innerJoinWith("models")
            ->where(['in', 'log.object_type', $object_type])
            ///->andWhere(['log.private_user_id' => 0])
            ->andWhere(['no_model_changes' => false])
            //->andWhere([ 'in', 'action', [ 'sent_to_specialist', 'accepted_by_specialist', 'edit', 'declined', 'accepted', 'declined_by_specialist' ] ])
            ->andWhere(['not in', 'log.action', self::EXCLUDED_ACTIONS])
            ->orderBy(['log.id' => SORT_ASC, 'log.object_id' => SORT_ASC]);

        //Выборка заявок только полностью согласованных
        if ($is_full_agreement_period_type) {
            $query->innerJoinWith(["models.report report" => function ($query) {
                $query->andWhere(['report.status' => 'accepted']);
            }]);

            //$query->andWhere(['models.status' => true, 'models.report.status' => true]);
        }

        if (!empty($filter_model_type)) {
            $filter_model_type == 'exclude_video'
                ? $query->andWhere(['!=', "model_category_id", 3])
                : $query->andWhere(['=', "model_category_id", 3]);
        }

        return $query;
    }

    /**
     * Получить статистику за период и тип заявки
     * @return array
     */
    public static function getModelsReportsData()
    {
        $result = [];
        $result_for_chart = [];

        $start_from = self::getStartDate();
        $end_from = self::getEndDate();

        //За какой период получаем данные по моделям
        $agreement_check_type = \Yii::$app->request->post("filter_models_check");

        $object_type = !empty(\Yii::$app->request->post("filter_object_type")) ? \Yii::$app->request->post("filter_object_type") : self::OBJECT_TYPE_MODEL;
        if ($object_type == self::OBJECT_TYPE_REPORT) {
            $object_type = [self::OBJECT_TYPE_REPORT, self::OBJECT_TYPE_REPORT_WITH_CONCEPT];
        } else {
            $object_type = [$object_type];
        }

        $filter_model_type = !empty(\Yii::$app->request->post("filter_model_type")) ? \Yii::$app->request->post("filter_model_type") : "";

        $result_count = [];
        $models_full_agreement_list = [];

        $diff = strtotime($end_from) - strtotime($start_from);
        $diff_days = floor($diff / (60 * 60 * 24));

        $dates_list = [];

        //Начальная дата
        $calc_date = date('Y-m-d', strtotime($start_from));
        for ($day = 1; $day <= $diff_days; $day++) {

            $query = self::makeQuery($object_type, $filter_model_type);
            $query->andWhere(['like', 'log.created_at', $calc_date]);

            $items_list = $query->all();
            foreach ($items_list as $item) {
                $item_date = date('Y-m-d', strtotime($item->created_at));

                //Сохраняем для вывода полной информации о согласовании модели (количество потраченных дней)
                $models_full_agreement_list[] = $item['object_id'];

                //Сохраняем пред. список дат
                if (!array_key_exists($item->object_id, $dates_list)) {
                    $dates_list[$item->object_id] = [];
                }

                if (!in_array($item_date, $dates_list[$item->object_id])) {
                    $dates_list[$item->object_id][] = $item_date;
                }

                $result[$item['object_id']][$item_date][$item->action] = ['status' => self::getModelStatusLabel($item["action"], $item), 'action' => $item->action, 'user_id' => $item->user_id, 'login' => $item->login, 'description' => $item->description];
            }

            $calc_date = $model_date = date('Y-m-d', strtotime('+ ' . $day . ' days', strtotime($start_from)));
        }


        //Проверка заявок на полный цикл выполнения заявок
        if ($agreement_check_type == Log::CHECK_TYPE_FULL_AGREEMENT_PERIOD) {
            return self::getFullAgreementModelsPeriod($models_full_agreement_list, $object_type, $filter_model_type);
        }

        //Делаем проверку на рабочий день
        //Если даты одинаковые, проверяем дату на выходной день
        $days_count = [];
        foreach ($dates_list as $object_id => $dates_items) {
            if (!array_key_exists($object_id, $days_count)) {
                $days_count[$object_id] = 0;
            }

            $days_count[$object_id] = self::calcDaysCount($dates_items) + self::calcDaysCount($dates_items, false);
        }

        //Проходим по полученному результату и получаем количество шагов при согласовании заявки
        //Каждый шаг, приваязан к событию - add или edit
        $models_result = [];
        $models_agreement_steps = [];
        foreach ($result as $model_id => $result_item_date) {
            foreach ($result_item_date as $item_date => $item_actions) {
                foreach ($item_actions as $action => $action_data) {
                    //Добавляем шаг согласования
                    if ($action == 'add' || $action == 'edit') {
                        $models_agreement_steps[$model_id][] = $action;
                    }

                    //Записываем информацию по согласованию
                    if (array_key_exists($model_id, $models_agreement_steps)) {
                        //Если нет записи в результирующем наборе, добавляем
                        if (!array_key_exists($model_id, $models_result)) {
                            $models_result[$model_id] = [];
                        }

                        $models_result[$model_id][count($models_agreement_steps[$model_id])][] = ['date' => $item_date, 'data' => $action_data];
                    }
                }
            }
        }

        //Проходим по полученным шагам и опредеяем время проверки заявки (согласование, отклонение)
        $models_result_with_steps = [];
        $result_for_chart_days = [];
        $model_colors = [];

        $min_days = time() * 2;
        $max_days = 0;

        foreach ($models_result as $model_id => $steps) {
            //Проходим по шагам и получаем начальную дату шага и конечную
            foreach ($steps as $step_key => $step_data_list) {

                //Проверяем, если есть первое действие
                if (isset($step_data_list[0])) {
                    $start_date = $step_data_list[0]['date'];

                    //Проверяем на второе действие
                    //Без второго действия заявку не фиксируем в конечном результате
                    if (count($step_data_list) > 1) {
                        if (!array_key_exists($model_id, $models_result_with_steps)) {
                            $models_result_with_steps[$model_id] = [];

                            if (count($steps) > 1) {
                                srand();
                                $model_colors[$model_id] = sprintf('%s,%s,%s,0.5', mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
                            }
                        }

                        $end_date = $step_data_list[count($step_data_list) - 1]['date'];

                        //$days = floor((strtotime($end_date) - strtotime($start_date)) / (60*60*24));
                        $days = self::calcDaysCount([$start_date, $end_date]) + self::calcDaysCount([$start_date, $end_date], false);

                        /*if (array_key_exists($model_id, $days_count)) {
                            $days = $days_count[$model_id];
                        }*/

                        //Суммируем общее количество дней по заявке
                        //Как рабочих так и выходных, если с заявкой велась работа в выходные
                        $days = $days == 0 ? 1 : $days;
                        //Минимальная дата в наборе
                        if ($days < $min_days) {
                            $min_days = $days;
                        }

                        //Максимальная дата в наборе
                        if ($days > $max_days) {
                            $max_days = $days;
                        }

                        $last_action = $step_data_list[count($step_data_list) - 1];

                        //Заполняем для диаграммы количество заявок выполненных за определенное количество дней
                        if (!isset($result_for_chart_days[$days])) {
                            $result_for_chart_days[$days] = 1;

                            $result_for_chart[sprintf("%s д%s", $days, \common\utils\NumbersHelper::numberEnd($days, array('ень', 'ня', 'ней')))] = 0;
                        }

                        //Заполняем количество заявко, с которыми работали менеджеры / дизайнеры
                        if (!isset($result_count[$days]["count"])) {
                            $result_count[$days]["count"] = 0;
                        }

                        if ($last_action['data']['action'] != 'add' && $last_action['data']['action'] != 'edit') {
                            $result_count[$days]["count"]++;
                            $result_for_chart[sprintf("%s д%s", $days, \common\utils\NumbersHelper::numberEnd($days, array('ень', 'ня', 'ней')))] += 1;
                        }

                        $models_result_with_steps[$model_id][$step_key] = [
                            'days' => $days,
                            'color' => isset($model_colors[$model_id]) ? $model_colors[$model_id] : '',
                            'steps_action' => [
                                'first' => $step_data_list[0],
                                'last' => $last_action
                            ]
                        ];

                    }
                }
            }
        }
        unset($models_result);

        ksort($result_for_chart);

        return [
            'models_list' => $models_result_with_steps,
            'chart_data' => $result_for_chart,
            'count' => $result_count,
            'min_days' => $min_days,
            'max_days' => $max_days
        ];
    }

    /**
     * @return false|mixed|string
     */
    public static function getStartDate()
    {
        return !empty(\Yii::$app->request->post("filter_start_date")) ? str_replace("/", "-", \Yii::$app->request->post("filter_start_date"))
            : date('Y-m-d', strtotime("-10 days", time()));
    }

    /**
     * @return false|mixed|string
     */
    public static function getEndDate()
    {
        return !empty(\Yii::$app->request->post("filter_end_date")) ? str_replace("/", "-", \Yii::$app->request->post("filter_end_date"))
            : date('Y-m-d');
    }

    /**
     * @param $status
     * @param $item
     * @return string
     */
    private static function getModelStatusLabel($status, $item)
    {
        $label = sprintf('%s (%s)', $item['description'], $item['created_at']);

        $statuses = [
            "add" => ["icon" => "mdi-av-play-arrow", "label" => $label],
            "edit" => ["icon" => "mdi-av-play-arrow", "label" => $label],
            "accepted" => ["icon" => "mdi-navigation-check", "label" => $label],
            "accepted_by_specialist" => ["icon" => "mdi-navigation-check", "label" => $label],
            //"add" => [ "icon" => "mdi-content-add", "label" => "Добавлен" ],
            "declined" => ["icon" => "mdi-content-block", "label" => $label],
            "declined_by_specialist" => ["icon" => "mdi-content-block", "label" => $label],
        ];

        if (array_key_exists($status, $statuses)) {
            return self::formatLabel($statuses[$status]["icon"], $statuses[$status]["label"]);
        }

        return "";
    }

    /**
     * @param $icon
     * @param $label
     * @return string
     */
    private static function formatLabel($icon, $label)
    {
        return "<span class='tooltipped' data-position='top' data-delay='50' data-tooltip='" . $label . "' ><i class='" . $icon . " ' /></span>";
    }

    /**
     * Вычисляем количестов дней (рабочих, выходных)
     * @param $model_dates
     * @param bool $workDays
     * @return int
     */
    private static function calcDaysCount($model_dates, $workDays = true)
    {
        $days_count = 0;
        if (count($model_dates) > 1) {
            $begin_agreement_date = strtotime(array_shift($model_dates));
            $end_agreement_date = strtotime(array_pop($model_dates));

            while (1) {
                //Если пройдены все дни, выходим
                if ($begin_agreement_date >= $end_agreement_date) {
                    break;
                }

                //Проверка на день (рабочий или выходной)
                //Для выходного делаем доп. проверку, если день есть в списке дат, учитывваем его
                if (!$workDays) {
                    if (in_array($begin_agreement_date, $model_dates)) {
                        $days_count++;
                    }
                } else {
                    if (D::workDay($begin_agreement_date)) {
                        $days_count++;
                    }
                }

                //Переходим на сл день
                $begin_agreement_date = strtotime(date('Y-m-d H:i:s', strtotime('+1 day', $begin_agreement_date)));
            }
        }

        return $days_count;
    }

    /**
     * Получить список дат, только тех которые рабочие
     */
    private static function calcHoursSum($model_dates, $model_id = 0)
    {
        if (count($model_dates) > 1) {
            $begin_agreement_date = strtotime(array_shift($model_dates));
            $end_agreement_date = strtotime(array_pop($model_dates));

            $hour_of_action_start = (int)date("H", $begin_agreement_date);
            $hour_of_action_complete = (int)date("H", $end_agreement_date);
            $minutes_of_action_complete = (int)date("i", $end_agreement_date);
            $minutes_of_action_start = (int)date("i", $begin_agreement_date);

            //Получаем даты без времени создания заявки
            $begin_agreement_date_without_hours = strtotime(date('Y-m-d', $begin_agreement_date));
            $end_agreement_date_without_hours = strtotime(date('Y-m-d', $end_agreement_date));


            //Если время выполнения равное (часы), устанавливаем в 0
            if ($hour_of_action_start == $hour_of_action_complete) {
                $hour_of_action_complete = 0;
            } else {

                //Если время выполнения заявки больше чем окончание рабочего ня
                if ($hour_of_action_complete >= self::TIME_WORK_END) {
                    //Если время отправки больше чем конец рабочего дня, устанавливаем на начало рабочего дня
                    if ($hour_of_action_start > self::TIME_WORK_END) {
                        $hour_of_action_start = self::TIME_WORK_BEGIN;
                    }

                    if ($hour_of_action_start < self::TIME_WORK_BEGIN) {
                        $hour_of_action_start = self::TIME_WORK_BEGIN;
                    }

                    $hour_of_action_complete = (self::TIME_WORK_END - $hour_of_action_start);
                    //$hour_of_action_complete = $hour_of_action_complete > 0 ? $hour_of_action_complete - 1 : $hour_of_action_complete;

                } else {
                    //Учитываем количество потраченного времени на проверку завки
                    if ($hour_of_action_complete > self::TIME_WORK_BEGIN && $hour_of_action_complete < self::TIME_WORK_END) {
                        if ($minutes_of_action_complete > 30) {
                            $hour_of_action_complete++;
                        }

                        if ($minutes_of_action_start > 30) {
                            $hour_of_action_start++;
                        }

                        //Если время выполнения меньше времени отправки заявки на согласование, учитываем потраченное время от начала работы менеджера (10 часов)
                        if ($hour_of_action_complete < $hour_of_action_start) {
                            $hour_of_action_complete = $hour_of_action_complete - self::TIME_WORK_BEGIN;
                        } //Если потраченное время больше времени отправки на согласование, учитываем потраченное время вычитание завршенного времени от времени отправки заявки на согласование
                        else if ($hour_of_action_complete > $hour_of_action_start) {
                            if ($hour_of_action_start >= self::TIME_WORK_BEGIN) {
                                $hour_of_action_complete = $hour_of_action_complete - $hour_of_action_start;
                            } else {
                                $hour_of_action_complete = $hour_of_action_complete - self::TIME_WORK_BEGIN;
                            }
                        }
                        //Если время выполнения заявки в тот же период времени (одинаковые часы)
                        //Делаем проврку на минуты выполнения, если больше 30 мин, устанавливаем время выполнения в 0 ч, иначе в 1 ч
                        else if ($hour_of_action_complete == $hour_of_action_start) {
                            $hour_of_action_complete = 1;
                            if ($minutes_of_action_complete > 30) {
                                $hour_of_action_complete = 0;
                            }
                        }
                    } else {
                        $hour_of_action_complete = 0;
                    }
                }
            }

            $total_work_days = 0;
            while (1) {
                //Если пройдены все дни, выходим
                if ($begin_agreement_date_without_hours >= $end_agreement_date_without_hours) {
                    break;
                }

                //Проверка на календарный день
                if (!D::checkDateInCalendar($begin_agreement_date, true)) {
                    //Только если рабочий день
                    if (D::workDay($begin_agreement_date)) {
                        $total_work_days++;
                    }
                }


                //Переходим на сл день
                $begin_agreement_date_without_hours = strtotime(date('Y-m-d', strtotime('+1 day', $begin_agreement_date)));
                $begin_agreement_date = strtotime(date('Y-m-d H:i:s', strtotime('+1 day', $begin_agreement_date)));;
            }
        }

        //Общеее количество часов потраченных на рссмотрение заявки
        return $total_work_days > 1 ? ((self::TIME_WORK_END - self::TIME_WORK_BEGIN) * $total_work_days) + $hour_of_action_complete : $hour_of_action_complete;
    }

    /**
     * Расчет прошедших часов после отправки макета на согласование
     * @param $model_dates
     * @param int $model_id
     * @return float|int
     */
    private static function calcFullHoursBeetwenDates($model_dates, $model_id = 0) {
        if (count($model_dates) > 1) {
            $begin_agreement_date = strtotime(array_shift($model_dates));
            $end_agreement_date = strtotime(array_pop($model_dates));

            $hour_of_action_start = (int)date("H", $begin_agreement_date);
            $hour_of_action_complete = (int)date("H", $end_agreement_date) - $hour_of_action_start;

            //Получаем даты без времени создания заявки
            $begin_agreement_date_without_hours = strtotime(date('Y-m-d', $begin_agreement_date));
            $end_agreement_date_without_hours = strtotime(date('Y-m-d', $end_agreement_date));

            $total_work_hours = 0;
            while (1) {
                //Если пройдены все дни, выходим
                if ($begin_agreement_date_without_hours >= $end_agreement_date_without_hours) {
                    break;
                }

                //Проверка на календарный день
                if (!D::checkDateInCalendar($begin_agreement_date, true)) {
                    //Только если рабочий день
                    if (D::workDay($begin_agreement_date)) {
                        $total_work_hours += 24;
                    }
                }

                //Переходим на сл день
                $begin_agreement_date_without_hours = strtotime(date('Y-m-d', strtotime('+1 day', $begin_agreement_date)));
                $begin_agreement_date = strtotime(date('Y-m-d H:i:s', strtotime('+1 day', $begin_agreement_date)));;
            }
        }

        //Общеее количество часов потраченных на рссмотрение заявки
        return $total_work_hours > 1 ? $total_work_hours + $hour_of_action_complete : $hour_of_action_complete;
    }
}
