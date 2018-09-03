<?php

namespace common\models\agreement_model;

use Yii;

/**
 * This is the model class for table "agreement_model_dates".
 *
 * @property integer $id
 * @property integer $activity_id
 * @property integer $model_id
 * @property integer $dealer_id
 * @property string $date_of
 */
class AgreementModelDates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agreement_model_dates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id', 'model_id', 'dealer_id', 'date_of'], 'required'],
            [['activity_id', 'model_id', 'dealer_id'], 'integer'],
            [['date_of'], 'string', 'max' => 40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_id' => 'Activity ID',
            'model_id' => 'Model ID',
            'dealer_id' => 'Dealer ID',
            'date_of' => 'Date Of',
        ];
    }
}
