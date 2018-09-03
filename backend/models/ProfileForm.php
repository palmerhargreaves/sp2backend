<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Shortest version of UserForm
 */
class ProfileForm extends Model
{
    public $first_name;
    public $last_name;
    public $phone;
    public $email;

    /**
     * @var User
     */
    private $_user;

    public function __construct(User $model, array $config = [])
    {
        parent::__construct($config);

        $this->_user = $model;

        $this->first_name = $model->first_name;
        $this->last_name = $model->last_name;
        $this->phone = $model->phone;
        $this->email = $model->email;
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
            ['phone', 'filter', 'filter' => 'trim'],
            ['phone', 'required'],
            ['phone', 'unique', 'targetClass'=> User::className(), 'filter' => function ($query) {
                if (!$this->user->isNewRecord) {
                    $query->andWhere(['not', ['id' => $this->user->id]]);
                }
            }],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass'=> User::className(), 'filter' => function ($query) {
                if (!$this->user->isNewRecord) {
                    $query->andWhere(['not', ['id' => $this->user->id]]);
                }
            }],
            [['first_name'], 'required'],
            [['first_name', 'last_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'first_name' => Yii::t('app', 'Имя'),
            'last_name' => Yii::t('app', 'Фамилия'),
            'phone' => Yii::t('app', 'Телефон'),
            'email' => Yii::t('app', 'Email')
        ];
    }

    /**
     * @return bool
     */
    public function save()
    {
        if(!$this->validate()) {
            return false;
        }

        $model = $this->getUser();

        $model->first_name = $this->first_name;
        $model->last_name = $this->last_name;
        $model->phone = $this->phone;
        $model->email = $this->email;

        return $model->save();
    }
}