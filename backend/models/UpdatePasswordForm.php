<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;

class UpdatePasswordForm extends Model
{
    public $new_password;

    /**
     * @var User
     */
    private $_user;

    public function __construct(User $model, array $config = [])
    {
        parent::__construct($config);

        $this->_user = $model;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['new_password', 'required'],
            ['new_password', 'string', 'min' => 4]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'new_password' => Yii::t('app', 'Новый пароль')
        ];
    }

    public function save()
    {
        if(!$this->validate()) {
            return false;
        }

        $user = $this->_user;
        $user->setPassword($this->new_password);
        $user->removePasswordResetToken();

        return $user->save(false);
    }
}