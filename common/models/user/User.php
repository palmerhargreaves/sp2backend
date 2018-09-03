<?php

namespace common\models\user;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

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
 * @property integer $company_department
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
 * @property integer $allow_to_get_dealers_messages
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
 * @property integer $natural_person_id
 * @property integer $is_default_specialist
 * @property integer $allow_to_receive_messages_in_chat
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const DESIGNER = '22';

    const ADMIN_GROUP = 1;
    const IMPORTER_GROUP = 2;
    const USER_GROUP_DEALER = 3;
    const REG_MANAGER_GROUP = 28;

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
            [['group_id', 'active', 'company_department', 'agreement_notification', 'final_agreement_notification', 'dealer_discussion_notification', 'model_discussion_notification', 'new_agreement_notification', 'registration_notification', 'agreement_report_notification', 'new_agreement_report_notification', 'final_agreement_report_notification', 'agreement_concept_notification', 'new_agreement_concept_notification', 'final_agreement_concept_notification', 'agreement_concept_report_notification', 'new_agreement_concept_report_notification', 'final_agreement_concept_report_notification', 'allow_to_get_dealers_messages', 'special_budget_status', 'prod_of_year3', 'allow_receive_mails', 'is_first_login', 'vwtraining_login', 'natural_person_id', 'is_default_specialist', 'allow_to_receive_messages_in_chat'], 'integer'],
            [['email', 'password'], 'required'],
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
            'company_department' => 'Company Department',
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
            'allow_to_get_dealers_messages' => 'Allow To Get Dealers Messages',
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
            'natural_person_id' => 'Natural Person ID',
            'is_default_specialist' => 'Is Default Specialist',
            'allow_to_receive_messages_in_chat' => 'Allow To Receive Messages In Chat',
        ];
    }

    /**
     * @param $email
     * @return $this
     * @internal param $login
     */
    public static function findByUsername($email) {
        return static::findOne(['email' => $email, 'active' => true]);
    }

    /**
     * Validate password
     * @param $password
     * @return bool
     */
    public function validatePassword($password) {
        if (empty($password) || is_null($password)) {
            return false;
        }

        return md5($password) == $this->password;
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'active' => true]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

    public function getFullName() {
        return sprintf('%s %s', $this->surname, $this->name);
    }

    public static function getUsersIdsByGroup($groupId) {
        return array_map(function($item) {
            return $item->id;
        }, self::find()->where(['group_id' => $groupId])->all());
    }
}
