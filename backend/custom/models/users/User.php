<?php

namespace app\custom\models\users;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property integer $group_id
 * @property string $email
 * @property string $password
 * @property string $name
 * @property string $surname
 * @property integer $active
 * @property string $created_at
 * @property string $updated_at
 * @property string $post
 * @property string $phone
 * @property string $recovery_key
 * @property string $patronymic
 * @property string $mobile
 * @property string $company_type
 * @property string $company_name
 * @property integer $agreement_notification
 * @property integer $final_agreement_notification
 * @property integer $dealer_discussion_notification
 * @property integer $model_discussion_notification
 * @property integer $new_agreement_notification
 * @property integer $registration_notification
 * @property integer $agreement_report_notification
 * @property integer $new_agreement_report_notification
 * @property integer $final_agreement_report_notification
 * @property integer $agreement_concept_notification
 * @property integer $new_agreement_concept_notification
 * @property integer $final_agreement_concept_notification
 * @property integer $agreement_concept_report_notification
 * @property integer $new_agreement_concept_report_notification
 * @property integer $final_agreement_concept_report_notification
 * @property string $activation_key
 * @property double $special_budget_quater
 * @property double $special_budget_summ
 * @property string $special_budget_date_of
 * @property integer $special_budget_status
 * @property string $summer_action_start_date
 * @property string $summer_action_end_date
 * @property string $summer_service_action_start_date
 * @property string $summer_service_action_end_date
 * @property integer $prod_of_year3
 * @property integer $allow_receive_mails
 * @property integer $is_first_login
 * @property integer $vwtraining_login
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'active', 'agreement_notification', 'final_agreement_notification', 'dealer_discussion_notification', 'model_discussion_notification', 'new_agreement_notification', 'registration_notification', 'agreement_report_notification', 'new_agreement_report_notification', 'final_agreement_report_notification', 'agreement_concept_notification', 'new_agreement_concept_notification', 'final_agreement_concept_notification', 'agreement_concept_report_notification', 'new_agreement_concept_report_notification', 'final_agreement_concept_report_notification', 'special_budget_status', 'prod_of_year3', 'allow_receive_mails', 'is_first_login', 'vwtraining_login'], 'integer'],
            [['email', 'password', 'created_at', 'updated_at', 'company_type', 'special_budget_date_of', 'summer_action_start_date', 'summer_action_end_date', 'summer_service_action_start_date', 'summer_service_action_end_date'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['company_type'], 'string'],
            [['special_budget_quater', 'special_budget_summ'], 'number'],
            [['email', 'password', 'name', 'surname', 'post', 'phone', 'recovery_key', 'patronymic', 'mobile', 'company_name', 'activation_key'], 'string', 'max' => 255],
            [['special_budget_date_of', 'summer_action_start_date', 'summer_action_end_date', 'summer_service_action_start_date', 'summer_service_action_end_date'], 'string', 'max' => 30],
            [['email'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_id' => 'Group ID',
            'email' => 'Email',
            'password' => 'Password',
            'name' => 'Name',
            'surname' => 'Surname',
            'active' => 'Active',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'post' => 'Post',
            'phone' => 'Phone',
            'recovery_key' => 'Recovery Key',
            'patronymic' => 'Patronymic',
            'mobile' => 'Mobile',
            'company_type' => 'Company Type',
            'company_name' => 'Company Name',
            'agreement_notification' => 'Agreement Notification',
            'final_agreement_notification' => 'Final Agreement Notification',
            'dealer_discussion_notification' => 'Dealer Discussion Notification',
            'model_discussion_notification' => 'Model Discussion Notification',
            'new_agreement_notification' => 'New Agreement Notification',
            'registration_notification' => 'Registration Notification',
            'agreement_report_notification' => 'Agreement Report Notification',
            'new_agreement_report_notification' => 'New Agreement Report Notification',
            'final_agreement_report_notification' => 'Final Agreement Report Notification',
            'agreement_concept_notification' => 'Agreement Concept Notification',
            'new_agreement_concept_notification' => 'New Agreement Concept Notification',
            'final_agreement_concept_notification' => 'Final Agreement Concept Notification',
            'agreement_concept_report_notification' => 'Agreement Concept Report Notification',
            'new_agreement_concept_report_notification' => 'New Agreement Concept Report Notification',
            'final_agreement_concept_report_notification' => 'Final Agreement Concept Report Notification',
            'activation_key' => 'Activation Key',
            'special_budget_quater' => 'Special Budget Quater',
            'special_budget_summ' => 'Special Budget Summ',
            'special_budget_date_of' => 'Special Budget Date Of',
            'special_budget_status' => 'Special Budget Status',
            'summer_action_start_date' => 'Summer Action Start Date',
            'summer_action_end_date' => 'Summer Action End Date',
            'summer_service_action_start_date' => 'Summer Service Action Start Date',
            'summer_service_action_end_date' => 'Summer Service Action End Date',
            'prod_of_year3' => 'Prod Of Year3',
            'allow_receive_mails' => 'Allow Receive Mails',
            'is_first_login' => 'Is First Login',
            'vwtraining_login' => 'Vwtraining Login',
        ];
    }
}
