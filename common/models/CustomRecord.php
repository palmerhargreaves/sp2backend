<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 25.11.2017
 * Time: 9:14
 */

namespace common\models;


class CustomRecord extends \yii\db\ActiveRecord
{

    protected function getReqVal($param) {
        return \Yii::$app->request->get($param) ? Yii::$app->request->get($param) : Yii::$app->request->post($param);
    }
}