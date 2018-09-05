<?php

namespace common\models\activity\statistic;

use common\models\activity\ActivityExtendedStatisticSections;

class ActivitySettingsBlock extends ActivityExtendedStatisticSections
{
    /**
     * @inheritdoc
     */
    public function rules ()
    {
        return [
            [['description'], 'required'],
            [['description'], 'string'],
        ];
    }

}
