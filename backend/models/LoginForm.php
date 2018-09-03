<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\user\User;

class LoginForm extends Model
{
    public $login;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // phone and password are both required
            [['login', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            //
            // password is validated by validatePassword()

            ['password', 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'login' => Yii::t('app', 'Логин'),
            'password' => Yii::t('app', 'Пароль'),
            'rememberMe' => Yii::t('app', 'Запомнить меня'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app', 'Неверный пароль.'));
            }
        }
    }

    /**
     * Logs in a user using the provided phone and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        $user = $this->getUser();
        if ($this->validate() && $user) {
            return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[phone]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->login);
        }

        return $this->_user;
    }

}
