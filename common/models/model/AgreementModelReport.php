<?php

namespace common\models\model;

use Yii;

/**
 * This is the model class for table "agreement_model_report".
 *
 * @property integer $id
 * @property integer $model_id
 * @property string $financial_docs_file
 * @property string $additional_file
 * @property string $agreement_comments
 * @property string $agreement_comments_file
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $decline_reason_id
 * @property string $accept_date
 * @property integer $accept_processed
 * @property string $manager_status
 * @property string $designer_status
 */
class AgreementModelReport extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agreement_model_report';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id', 'status', 'created_at', 'updated_at', 'accept_processed' ], 'required'],
            [['model_id', 'decline_reason_id', 'accept_processed'], 'integer'],
            [['agreement_comments', 'status', 'manager_status', 'designer_status'], 'string'],
            [['created_at', 'updated_at', 'accept_date'], 'safe'],
            [['financial_docs_file', 'additional_file', 'agreement_comments_file' ], 'string', 'max' => 255],
            [['model_id'], 'unique'],
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
            'financial_docs_file' => 'Financial Docs File',
            'additional_file' => 'Additional File',
            'agreement_comments' => 'Agreement Comments',
            'agreement_comments_file' => 'Agreement Comments File',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'decline_reason_id' => 'Decline Reason ID',
            'accept_date' => 'Accept Date',
            'accept_processed' => 'Accept Processed',
            'manager_status' => 'Manager Status',
            'designer_status' => 'Designer Status',
        ];
    }
}
