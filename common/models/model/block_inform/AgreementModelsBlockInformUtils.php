<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 12.06.2018
 * Time: 10:26
 */

namespace common\models\model\block_inform;


use common\models\agreement_model\AgreementModel;
use common\models\AgreementModelValue;
use common\models\logs\Log;
use common\utils\D;
use common\utils\Utils;

class AgreementModelsBlockInformUtils
{
    const MODEL_10_DAYS_LEFT = 11;
    const MODEL_2_DAYS_LEFT = 3;
    const MODEL_BLOCKED = 'blocked';

    const MODEL_10_DAYS_LEFT_LABEL = 'left_10';
    const MODEL_2_DAYS_LEFT_LABEL = 'left_2';

    /**
     * Получаем информацию по заявкам которые будут заблокированы через определенные промежуток времени
     * Если остается 10 дней до выполнения заявки, отправляем предупреждающее письмо
     * Если остается 2 дней, также письмо с передцпреждением
     * Если < 0, блокируем
     * @return array
     */
    public static function getBlockInformation() {
        $models = AgreementModel::find()
            ->select([
                'id', 'status', 'is_blocked', 'allow_use_blocked', 'use_blocked_to', 'model_category_id', 'period'
            ])
            ->andFilterWhere([
                'year(agreement_model.created_at)' => [
                    date('Y'),
                    date('Y') - 1
                ],
                'status' => 'accepted',
                'is_deleted' => false
            ])
            ->orderBy(['id' => SORT_DESC])
            ->all();

        //Получаем текущую дату
        $today = strtotime(date('d-m-Y 00:00:00', strtotime('+1 days', time())));

        foreach ($models as $model) {
            //Если заявка заблокирована пропускаем ее
            if ($model->is_blocked && !$model->allow_use_blocked) {
                continue;
            }

            if ($model->status == 'accepted' && ($model->report && ($model->report->status == 'accepted' || $model->report->status == 'wait' || $model->report->status == 'wait_specialist'))) {
                continue;
            }

            /**Определяем количество дней до блокировки заявки */
            $model_period_end_value = date('d-m-Y', strtotime('+ 1 days', strtotime(AgreementModelValue::getPeriodValueFromModel($model))));
            $model_period_end = strtotime(D::getNewDate($model_period_end_value, self::MODEL_10_DAYS_LEFT , '+', false, 'd-m-Y'));

            /**
             * Если для заявки не загружен отчет
             */
            if (is_null($model->report)) {
                /**
                 * Если заявка разблокирована, проверяем дату окончания разблокировки, в случае если период окончен, блокируем заявку
                 */
                if ($model->is_blocked && !empty($model->use_blocked_to)) {
                    $model_period_end = strtotime($model->use_blocked_to);
                    $elapsed_days = Utils::getElapsedTime($model_period_end - $today);
                } else {
                    $model_period_end = strtotime(D::getNewDate($model_period_end_value, self::MODEL_10_DAYS_LEFT, '+', false, 'd-m-Y H:i:s', 0));
                    $elapsed_days = Utils::getElapsedTime($model_period_end - $today);
                }

                //Делаем проверку на выходные дни
                $not_work_days = 0;
                for($day_index = 0; $day_index <= $elapsed_days; $day_index++) {
                    $tempDate = date('Y-m-d H:i:s', strtotime('-' . $day_index . ' days', D::toUnix($model_period_end)));

                    $d = getdate(strtotime($tempDate));
                    $dPlus = D::checkDateInCalendar($tempDate);
                    if ($dPlus == 0) {
                        if ($d['wday'] == 0 || $d['wday'] == 6)
                            $not_work_days++;
                    } else if ($dPlus > 1) {
                        $day_index += $dPlus;
                    }
                }

                $elapsed_days -= $not_work_days;

                //Вычитаем полученные рабочие дни
                ///$days -= $not_work_days;

                if ($elapsed_days <= 10 && $elapsed_days > 2) {
                    //$this->addDealerNotificationAndItem($model, AgreementModelsBlockInform::INFORM_STATUS_LEFT_10, true, $elapsed_days, $model_period_end);
                    echo ('add_10_report_left'.$model->id);
                } else if ($elapsed_days <= 2 && $elapsed_days > 0) {
                    //$this->addDealerNotificationAndItem($model, AgreementModelsBlockInform::INFORM_STATUS_LEFT_2, true, $elapsed_days, $model_period_end);
                    echo ('add_2_report_left'.$model->id);
                }
                /**
                 * Если есть отчет и до выполнения осталось 0 дней блокируем заявку
                 */
                else if ($elapsed_days <= 0) {
                    //$this->addDealerNotificationAndItem($model, AgreementModelsBlockInform::INFORM_STATUS_BLOCKED, true, 0, $model_period_end);

                    echo ('add_blocked_report'.$model->id);
                }

                var_dump($model->id, 'without report', $elapsed_days);exit;
            } else {
                /**
                 * Делаем проверку после истечения срока размещения, если осталось 0 дней блокируем заявку и отправляем письмо
                 * Если завка была разблокирована учитываем дату разблокировки
                 */
                if ($model->is_blocked && !empty($model->use_blocked_to)) {
                    $model_period_end = $model->use_blocked_to;
                } else {
                    //Если текущая дата больше чем перриод размещения, получаем дату с момента отправки отчета на согласование
                    if ($today > $model_period_end) {
                        $log_entry = Log::find()->select(['created_at'])
                            ->where(['object_id' => $model->id, 'action' => 'declined', 'object_type' => 'agreement_report'])
                            ->orderBy(['id' => SORT_DESC])
                            ->asArray();
                        if ($log_entry) {
                            $value = date('d-m-Y H:i:s', strtotime($log_entry['created_at']));
                            $model_period_end = strtotime(D::getNewDate($value, 2, '+', false, 'd-m-Y H:i:s'));
                        }
                    }
                }

                $elapsed_days = Utils::getElapsedTime($model_period_end - $today);

                //Делаем проверку на выходные дни
                $not_work_days = 0;
                for($day_index = 0; $day_index <= $elapsed_days; $day_index++) {
                    $tempDate = date('Y-m-d H:i:s', strtotime('-' . $day_index . ' days', D::toUnix($model_period_end)));

                    $d = getdate(strtotime($tempDate));
                    $dPlus = D::checkDateInCalendar($tempDate);
                    if ($dPlus == 0) {
                        if ($d['wday'] == 0 || $d['wday'] == 6)
                            $not_work_days++;
                    } else if ($dPlus > 1) {
                        $day_index += $dPlus;
                    }
                }

                $elapsed_days -= $not_work_days;

                /**
                 * Если есть отчет и осталось до выполнения 10 дней, информируем дилера о 10 днях
                 */
                if ($elapsed_days <= 10 && $elapsed_days > 2) {
                    //$this->addDealerNotificationAndItem($model, AgreementModelsBlockInform::INFORM_STATUS_LEFT_10, true, $elapsed_days, $model_period_end);
                    echo ('add_10_report_left'.$model->id);
                }
                /**
                 * Если есть отчет и осталось до выполнения 2 дня, информируем дилера о двух днях
                 */
                else if (!is_null($model->report) && $model->report->status == 'declined' && $elapsed_days <= 2 && $elapsed_days > 0) {
                    //$this->addDealerNotificationAndItem($model, AgreementModelsBlockInform::INFORM_STATUS_LEFT_2, true, $elapsed_days, $model_period_end);

                    echo ('add_2_report'.$model->id);
                }
                /**
                 * Если есть отчет и до выполнения осталось 0 дней блокируем заявку
                 */
                else if (!is_null($model->report) && $model->report->status == 'declined' && $elapsed_days <= 0) {
                    //$this->addDealerNotificationAndItem($model, AgreementModelsBlockInform::INFORM_STATUS_BLOCKED, true, 0, $model_period_end);
                    echo ('add_blocked_report'.$model->id);
                }

                var_dump($model->id, 'with report');exit;

            }

        }

        return [];
    }
}
