<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 25.11.2017
 * Time: 10:12
 */

namespace common\models\activity\fields\traits;


use common\models\activity\ActivityExtendedStatisticFieldsData;

trait ActivityFieldCalcFieldsTrait
{
    public function getCalcValue($dealer_id, $activity_id, $step_id, $quarter = 0) {
        $calc_fields = $this->getCalcFields();
        $values = [];

        $concept_id = 0;
        $calc_values = [];
        foreach ($calc_fields as $calc_field) {
            $query = ActivityExtendedStatisticFieldsData::find()->where(['dealer_id' => $dealer_id, 'field_id' => $calc_field->calc_field, 'activity_id' => $activity_id, 'step_id' => $step_id]);

            if ($quarter != 0) {
                $query->andWhere(['quarter' => $quarter]);
            }
            $value_items = $query->all();

            foreach ($value_items as $value_item) {
                if ($value_item) {
                    $calc_values[] = floatval($value_item->value);

                    $concept_id = $value_item->concept_id;
                }
                $calc_func = $calc_field->calc_type;

                if (empty($calc_values)) {
                    return [];
                }

                if (count($calc_values) < 2) {
                    $values[$concept_id] = array_values(array_filter($calc_values));
                }

                if (method_exists($this, $calc_func)) {
                    $values[$concept_id] = $this->$calc_func($calc_values);
                }
            }
        }

        return $values;
    }

    protected function plus($values) {
        $result = 0;
        foreach ($values as $val) {
            $result += $val;
        }

        return $result;
    }

    protected function minus($values) {
        $result = 0;
        foreach ($values as $val) {
            $result -= $val;
        }

        return $result;
    }

    protected function divide($values) {
        return isset($values[1]) && $values[1] != 0 ? $values[0] / $values[1] : 0;
    }

    protected function multiple($values) {
        $result = 0;
        foreach ($values as $val) {
            $result *= $val;
        }

        return $result;
    }

    protected function percent($values) {
        return isset($values[1]) && $values[1] != 0 ? $values[0] * 100 / $values[1] : 0;
    }

}