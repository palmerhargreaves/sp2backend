<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\user\User;
use yii\helpers\ArrayHelper;

/**
 * @property $user User
 */
class UserForm extends Model
{
    public $username;
    public $firstname;
    public $surname;
    public $phone;
    public $email;
    public $password;
    public $status;
    public $description;
    public $enabled;
    public $last_visit_at;
    public $created_at;

    public $role;

    /**
     * @var User
     */
    private $_user;

    public function __construct(User $model, array $config = [])
    {
        parent::__construct($config);

        $this->_user = $model;

        $this->username = $model->username;
        $this->firstname = $model->firstname;
        $this->surname = $model->surname;
        $this->phone = $model->phone;
        $this->email = $model->email;
        $this->status = $model->status;

        $this->description = $model->description;
        $this->enabled = $model->enabled;

        $role = $model->getRole();
        if($role) {
            $this->role = $role->name;
        }
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->_user;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => User::className(), 'filter' => function($query) {
                $query->andWhere(['not', ['username' => $this->user->username]]);
            }],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass'=> User::className(), 'filter' => function ($query) {
                if (!$this->user->isNewRecord) {
                    $query->andWhere(['not', ['id' => $this->user->id]]);
                }
            }],
            ['password', 'required', 'on' => 'create'],
            ['password', 'string', 'min' => 4],
            [['firstname', 'surname'], 'string', 'max' => 255],

            ['status', 'integer'],

            [['role'], 'required'],
            [['role'], 'in', 'range' => ArrayHelper::getColumn(
                Yii::$app->authManager->getRoles(),
                'name'
            )],

            [['description'], 'trim'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Логин'),
            'firstname' => Yii::t('app', 'Имя'),
            'surname' => Yii::t('app', 'Фамилия'),
            'phone' => Yii::t('app', 'Телефон'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Статус'),
            'password' => Yii::t('app', 'Пароль'),
            'role' => Yii::t('app', 'Роль'),
        ];
    }

    /**
     * @return bool
     */
    public function save()
    {
        if(!$this->validate() && $this->hasErrors()) {
            Yii::$app->session->setFlash('error', 'Ошибка сохранения параметров пользователя.');
            return false;
        }

        $model = $this->getUser();

        $model->firstname = $this->firstname;
        $model->surname = $this->surname;
        $model->email = $this->email;

        $model->status = $this->status;

        $model->description = $this->description;

        $model->role = $this->role;
        $model->enabled = $this->enabled;

        if (!empty($this->password)) {
            $model->setPassword($this->password);
        }

        if(!$model->save()) {
            return false;
        }

        $auth = Yii::$app->authManager;
        $auth->revokeAll($model->getId());

        if ($this->role) {
            $auth->assign($auth->getRole($this->role), $model->getId());
        }

        return true;
    }
}
