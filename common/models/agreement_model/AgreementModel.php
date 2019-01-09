<?php

namespace common\models\agreement_model;

use common\models\activity\Activity;
use common\models\agreement_model\traits\AgreementModelStatsTrait;
use common\models\AgreementModelValue;
use common\models\dealers\Dealers;
use common\models\Log;
use common\models\model\AgreementModelReport;
use Yii;

/**
 * This is the model class for table "agreement_model".
 *
 * @property integer $id
 * @property integer $activity_id
 * @property integer $dealer_id
 * @property string $name
 * @property integer $model_type_id
 * @property integer $original_model_type_id
 * @property integer $model_category_id
 * @property string $target
 * @property string $cost
 * @property string $period
 * @property string $agreement_comments
 * @property string $agreement_comments_file
 * @property string $model_file
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $report_id
 * @property integer $decline_reason_id
 * @property integer $discussion_id
 * @property integer $wait
 * @property integer $blank_id
 * @property integer $wait_specialist
 * @property integer $task_id
 * @property integer $editor_id
 * @property string $editor_link
 * @property integer $accept_in_model
 * @property integer $is_blocked
 * @property integer $allow_use_blocked
 * @property string $use_blocked_to
 * @property integer $blocked_inform
 * @property integer $no_model_changes
 * @property integer $model_accepted_in_online_redactor
 * @property string $step1
 * @property string $step2
 * @property string $model_record_file
 * @property string $model_file1
 * @property string $model_file2
 * @property string $model_file3
 * @property string $model_file4
 * @property string $model_file5
 * @property string $model_file6
 * @property string $model_file7
 * @property string $model_file8
 * @property string $model_file9
 * @property string $model_file10
 * @property string $model_record_file1
 * @property string $model_record_file2
 * @property string $model_record_file3
 * @property string $model_record_file4
 * @property string $model_record_file5
 * @property string $model_record_file6
 * @property string $model_record_file7
 * @property string $model_record_file8
 * @property string $model_record_file9
 * @property string $model_record_file10
 * @property integer $concept_id
 * @property string $share_name
 * @property string $manager_status
 * @property string $designer_status
 * @property integer $designer_approve
 * @property string $agreement_comment_manager
 * @property integer $is_necessarily_model
 * @property string $blocked_inform_status
 * @property string $user_agent
 * @property string $files_ids
 */
class AgreementModel extends \yii\db\ActiveRecord
{
    const DESIGNER_MAX_WORK_HOURS = 24;

    use AgreementModelStatsTrait;

    public $am_status;

    /**
     * @inheritdoc
     */
    public static function tableName ()
    {
        return 'agreement_model';
    }

    /**
     * @inheritdoc
     */
    public function rules ()
    {
        return [
            [ [ 'activity_id', 'dealer_id', 'name', 'model_type_id', 'period', 'status', 'created_at', 'updated_at', 'editor_id', 'editor_link', 'use_blocked_to', 'model_record_file', 'agreement_comment_manager', 'user_agent', 'files_ids' ], 'required' ],
            [ [ 'activity_id', 'dealer_id', 'model_type_id', 'original_model_type_id', 'model_category_id', 'report_id', 'decline_reason_id', 'discussion_id', 'wait', 'blank_id', 'wait_specialist', 'task_id', 'editor_id', 'accept_in_model', 'is_blocked', 'allow_use_blocked', 'blocked_inform', 'no_model_changes', 'model_accepted_in_online_redactor', 'concept_id', 'designer_approve', 'is_necessarily_model' ], 'integer' ],
            [ [ 'cost' ], 'number' ],
            [ [ 'agreement_comments', 'status', 'step1', 'step2', 'manager_status', 'designer_status', 'blocked_inform_status' ], 'string' ],
            [ [ 'created_at', 'updated_at' ], 'safe' ],
            [ [ 'name', 'target', 'period', 'agreement_comments_file', 'model_file', 'editor_link', 'model_record_file', 'share_name', 'agreement_comment_manager', 'user_agent', 'files_ids' ], 'string', 'max' => 255 ],
            [ [ 'use_blocked_to' ], 'string', 'max' => 30 ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels ()
    {
        return [
            'id' => '№',
            'activity_id' => 'Активность',
            'dealer_id' => 'Дилер',
            'name' => 'Название',
            'model_type_id' => 'Тип',
            'original_model_type_id' => 'Original Model Type ID',
            'model_category_id' => 'Категория / Тип',
            'target' => 'Target',
            'cost' => 'Цена',
            'period' => 'Период',
            'agreement_comments' => 'Agreement Comments',
            'agreement_comments_file' => 'Agreement Comments File',
            'model_file' => 'Model File',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'report_id' => 'Report ID',
            'decline_reason_id' => 'Decline Reason ID',
            'discussion_id' => 'Discussion ID',
            'wait' => 'Wait',
            'blank_id' => 'Blank ID',
            'wait_specialist' => 'Wait Specialist',
            'task_id' => 'Task ID',
            'editor_id' => 'Editor ID',
            'editor_link' => 'Editor Link',
            'accept_in_model' => 'Accept In Model',
            'is_blocked' => 'Is Blocked',
            'allow_use_blocked' => 'Allow Use Blocked',
            'use_blocked_to' => 'Use Blocked To',
            'blocked_inform' => 'Blocked Inform',
            'no_model_changes' => 'No Model Changes',
            'model_accepted_in_online_redactor' => 'Model Accepted In Online Redactor',
            'step1' => 'Step1',
            'step2' => 'Step2',
            'model_record_file' => 'Model Record File',
            'concept_id' => 'Concept ID',
            'share_name' => 'Share Name',
            'manager_status' => 'Manager Status',
            'designer_status' => 'Designer Status',
            'designer_approve' => 'Designer Approve',
            'agreement_comment_manager' => 'Agreement Comment Manager',
            'is_necessarily_model' => 'Is Necessarily Model',
            'blocked_inform_status' => 'Blocked Inform Status',
            'user_agent' => 'User Agent',
            'files_ids' => 'Files Ids',
        ];
    }

    public function getActivity ()
    {
        return $this->hasOne(Activity::className(), [ 'id' => 'activity_id' ]);
    }

    public function getDealer ()
    {
        return $this->hasOne(Dealers::className(), [ 'id' => 'dealer_id' ]);
    }

    public function getReport ()
    {
        return $this->hasOne(AgreementModelReport::className(), [ 'id' => 'report_id' ]);
    }

    public function getValues() {
        return $this->hasMany(AgreementModelValue::className(), ['model_id' => 'id']);
    }

    public function getLog() {
        return $this->hasMany(Log::className(), ['object_id' => 'id']);
    }
}
