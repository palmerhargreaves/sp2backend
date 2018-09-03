<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 22.11.2017
 * Time: 15:55
 */

namespace common\models\activity\traits;

use common\models\activity\ActivityDealerStatisticStatus;
use common\models\activity\ActivityExtendedStatisticFieldsData;
use common\models\activity\steps\ActivityExtendedStatisticStepValues;

trait ActivityExtendedStatisticTrait
{
    public function getExtendedStatisticTotalFieldsValuesFilledCount() {
        return $this->getExtendedStatisticDealersList(true);
    }

    public function getExtendedStatisticTotalFieldsValuesFilledCountBySteps() {

    }

    /**
     * Получить данные по заполненной стаститике (без шагов)
     * @param bool $count
     * @return array|int|string|\yii\db\ActiveRecord[]
     */
    public function getExtendedStatisticDealersList($count = false, $month = null) {
        $query = ActivityDealerStatisticStatus::find()->where(['activity_id' => $this->id])->andWhere(['using_steps' => true]);

        if (!is_null($month)) {
            $date = date('Y-m', strtotime(date('Y-'.$month)));

            $query->andWhere(['LIKE', 'updated_at', $date]);
        } else {
            $query->andWhere(['year(updated_at)' => date('Y')]);
        }

        return $count
            ? $query->count()
            : $query->all();
    }

    /**
     * Получить данные пл заполненной статистике (по шагам)
     * @return array|int|string|\yii\db\ActiveRecord[]
     * @internal param bool $count
     */
    public function getExtendedStatisticDealersListBySteps() {
        $result = [];

        $items = ActivityExtendedStatisticStepValues::find()->where(['activity_id' => $this->id])->andWhere(['year(created_at)' => date('Y')])->groupBy(['dealer_id'])->orderBy(['step_id' => SORT_ASC])->all();
        foreach ($items as $item) {
            if (!isset($result[$item['id']])) {
                $result[$item['id']] = [
                    'count' => 0,
                    'name' => $item->step->header
                ];
            }

            $result[$item['id']] = [
                'count' => $result[$item['id']]['count']++,
                'name' => $item->step->header
            ];
        }

        return $result;
    }

    public function getExtendedStatisticInfoYear() {
        $result = [];

        for($month = 1; $month <= 12 ; $month++) {
            $result[] = $this->getExtendedStatisticDealersList(true, $month);
        }

        return implode(':', $result);
    }
}