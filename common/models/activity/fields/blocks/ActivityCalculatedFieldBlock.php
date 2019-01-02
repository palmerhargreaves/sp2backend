<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 08.09.2018
 * Time: 11:34
 */

namespace common\models\activity\fields\blocks;


use common\models\activity\fields\ActivityExtendedStatisticFields;

class ActivityCalculatedFieldBlock extends ActivityExtendedStatisticFields
{
    /**
     * @inheritdoc
     */
    public function rules ()
    {
        return [
            [ [ 'header', 'activity_id', 'parent_id' ], 'required' ],
            [ [ 'value_type', ], 'string' ],
            [ [ 'activity_id', 'parent_id', 'status', ], 'integer' ],
            [ [ 'show_in_export', 'show_in_statistic', 'status', ], 'boolean' ],
            [ [ 'header', ], 'string', 'max' => 255 ],
        ];
    }
}
