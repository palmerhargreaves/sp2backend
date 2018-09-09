<?php

namespace common\models\activity\fields\blocks;

use common\models\activity\fields\ActivityExtendedStatisticFields;

class ActivityOtherIndicatorsBlock extends ActivityExtendedStatisticFields
{
    /**
     * @inheritdoc
     */
    public function rules ()
    {
        return [
            [ [ 'header', 'value_type', 'activity_id', 'parent_id' ], 'required' ],
            [ [ 'value_type' ], 'string' ],
            [ [ 'activity_id', 'parent_id', 'status', 'position', 'required', 'step_id' ], 'integer' ],
            [ [ 'show_in_export' ], 'boolean' ],
            [ [ 'header', 'description' ], 'string', 'max' => 255 ],
        ];
    }

}
