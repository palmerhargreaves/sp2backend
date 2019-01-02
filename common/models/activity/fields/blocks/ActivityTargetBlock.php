<?php

namespace common\models\activity\fields\blocks;

use common\models\activity\fields\ActivityExtendedStatisticFields;


class ActivityTargetBlock extends ActivityExtendedStatisticFields
{
    /**
     * @inheritdoc
     */
    public function rules ()
    {
        return [
            [ [ 'header', 'activity_id', 'parent_id', 'def_value', 'dealer_id' ], 'required' ],
            [ [ 'value_type', 'def_value' ], 'string' ],
            [ [ 'activity_id', 'parent_id', 'status', 'position', 'required', 'step_id', 'editable', 'dealer_id' ], 'integer' ],
            [ [ 'header', 'description' ], 'string', 'max' => 255 ],
            [ [ 'show_in_statistic' ], 'boolean' ]
        ];
    }


}
