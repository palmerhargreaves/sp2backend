<?php

namespace common\models\activity\sections;

use common\models\activity\fields\blocks\ActivityEffectivenessOfTheActionBlock;
use common\models\activity\fields\blocks\BaseBlockModel;

/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 03.09.2018
 * Time: 11:48
 */

class ActivitySectionEffectivenessOfTheAction extends BaseBlockModel {
    public function beforeSave($insert)
    {
        $this->header = is_null($this->header) ? 'Результативность акции' : $this->header;

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public function getModel() {
        return new ActivityEffectivenessOfTheActionBlock();
    }
}
