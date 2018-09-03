<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 05.03.2017
 * Time: 15:50
 */

namespace backend\components;

use backend\components\Traits\StatisticsTrait;
use common\components\TasksInterface;
use common\models\books\Books;
use yii\base\Component;

class TasksUtils extends Component implements TasksInterface
{
    use StatisticsTrait;

    /**
     * Получить список послледних созданных книг
     */
    public function getLastBooksList() {
        return Books::find()->limit(25)->orderBy(['id' => 'DESC'])->all();
    }


}
