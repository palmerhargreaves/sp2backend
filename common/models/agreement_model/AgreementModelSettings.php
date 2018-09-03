<?php

namespace common\models\agreement_model;

use Yii;

/**
 * This is the model class for table "agreement_model_settings".
 *
 * @property integer $id
 * @property integer $model_id
 * @property string $certificate_date_to
 * @property integer $msg_send
 * @property integer $activate_msg_send
 */
class AgreementModelSettings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agreement_model_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id', 'certificate_date_to'], 'required'],
            [['model_id', 'msg_send', 'activate_msg_send'], 'integer'],
            [['certificate_date_to'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model_id' => 'Model ID',
            'certificate_date_to' => 'Certificate Date To',
            'msg_send' => 'Msg Send',
            'activate_msg_send' => 'Activate Msg Send',
        ];
    }
}
