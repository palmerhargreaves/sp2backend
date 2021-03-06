<?php

namespace common\models\activity\fields\blocks;

use common\models\activity\fields\ActivityExtendedStatisticFields;

class ActivityEffectivenessOfTheActionBlock extends ActivityExtendedStatisticFields
{
    /**
     * @inheritdoc
     */
    public function rules ()
    {
        return [
            [ [ 'header', 'activity_id', 'parent_id' ], 'required' ],
            [ [ 'value_type' ], 'string' ],
            [ [ 'activity_id', 'parent_id', 'status', 'position', 'required', 'step_id' ], 'integer' ],
            [ [ 'show_in_export', 'show_in_statistic', 'is_revenue_field' ], 'boolean' ],
            [ [ 'header', 'description' ], 'string', 'max' => 255 ],
        ];
    }

}
