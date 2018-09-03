<?php

namespace common\models\logs;

use common\models\agreement_model\AgreementModel;
use common\models\logs\statistics\LogAgreementModelsReportsStatisticsTrait;

/**
 * This is the model class for table "log".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $description
 * @property integer $object_id
 * @property integer $module_id
 * @property string $action
 * @property string $created_at
 * @property string $object_type
 * @property integer $importance
 * @property string $login
 * @property integer $dealer_id
 * @property string $title
 * @property string $icon
 * @property integer $message_id
 * @property integer $private_user_id
 */
class Log extends \yii\db\ActiveRecord
{
    const OBJECT_TYPE_MODEL = 'agreement_model';
    const OBJECT_TYPE_REPORT = 'agreement_report';
    const OBJECT_TYPE_REPORT_WITH_CONCEPT = 'agreement_concept_report';
    const OBJECT_TYPE_MODEL_REPORT = 'agreement_model_report';

    const ACTION_ACCEPTED = 'accepted';
    const ACTION_ACCEPTED_BY_SPECIALIST = 'accepted_by_specialist';

    const EXCLUDED_ACTIONS = [ 'dealer_model_block_inform', 'cancel', 'delete', 'uploaded_file_delete', 'post', 'model_copy', 'model_move_to_activity_date' ];
    const CHECK_TYPE_FULL_AGREEMENT_PERIOD = 'agreement_period_all_days';

    const TIME_WORK_BEGIN = 10;
    const TIME_WORK_END = 19;

    use LogAgreementModelsReportsStatisticsTrait;

    /**
     * @inheritdoc
     */
    public static function tableName ()
    {
        return 'log';
    }

    /**
     * @inheritdoc
     */
    public function rules ()
    {
        return [
            [ [ 'user_id', 'description', 'created_at', 'login' ], 'required' ],
            [ [ 'user_id', 'object_id', 'module_id', 'importance', 'dealer_id', 'message_id', 'private_user_id' ], 'integer' ],
            [ [ 'description' ], 'string' ],
            [ [ 'created_at' ], 'safe' ],
            [ [ 'action', 'object_type', 'login', 'title', 'icon' ], 'string', 'max' => 255 ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels ()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'description' => 'Description',
            'object_id' => 'Object ID',
            'module_id' => 'Module ID',
            'action' => 'Action',
            'created_at' => 'Created At',
            'object_type' => 'Object Type',
            'importance' => 'Importance',
            'login' => 'Login',
            'dealer_id' => 'Dealer ID',
            'title' => 'Title',
            'icon' => 'Icon',
            'message_id' => 'Message ID',
            'private_user_id' => 'Private User ID',
        ];
    }

    public function getModels ()
    {
        return $this->hasMany(AgreementModel::className(), [ 'id' => 'object_id' ]);
    }
}
