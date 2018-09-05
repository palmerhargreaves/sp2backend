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
            [ [ 'header', 'activity_id', 'def_value', 'dealers_group' ], 'required' ],
            [ [ 'value_type', 'def_value', 'dealers_group' ], 'string' ],
            [ [ 'activity_id', 'parent_id', 'status', 'position', 'required', 'step_id', 'editable' ], 'integer' ],
            [ [ 'header', 'description' ], 'string', 'max' => 255 ],
        ];
    }

}
