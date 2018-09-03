<?php

namespace common\models;

use backend\components\helpers\FilesHelper;
use common\custom\CustomActiveRecord;
use common\models\messages\Message;
use common\models\user\User;
use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "contacts".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $name_parental_case
 * @property string $photo
 * @property string $duties
 * @property string $description
 * @property string $color
 * @property integer $status
 * @property integer $position
 */
class Contacts extends CustomActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contacts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'duties', 'description'], 'required'],
            [['description', 'name_parental_case', 'color'], 'string'],
            [['status', 'user_id', 'position'], 'integer'],
            [['name', 'photo', 'duties'], 'string', 'max' => 255],
            ['photo', 'file', 'extensions' => 'jpg, jpeg, gif, png', 'mimeTypes' => ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'], 'maxFiles' => 1, 'skipOnEmpty' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'name_parental_case' => 'Имя (род. падеж)',
            'photo' => 'Изображение',
            'duties' => 'Должность',
            'description' => 'Описание',
            'color' => 'Цвет',
            'user_id' => 'Привязанный пользователь',
            'status' => 'Статус',
        ];
    }

    public function savePhoto() {
        //Сохранения изображения
        if (!empty($_FILES['Contacts']) &&!empty($_FILES['Contacts']['type']['photo'])) {
            $uploaded_files = UploadedFile::getInstances($this, 'photo');

            $dest_path = Yii::$app->params['basePathImages'];
            foreach ($uploaded_files as $uploaded_file) {
                $gen_file_helper = new FilesHelper($dest_path);
                $gen_file_name = $gen_file_helper->generate($uploaded_file->name);

                if ($uploaded_file->saveAs($dest_path . $gen_file_name)) {
                    $this->photo= $gen_file_name;
                    $this->save(false);
                }
            }

            $this->save(false);
        }
    }

    /**
     * Получить список сообщений пользователя
     */
    public function getMessagesList() {
        return Message::find()->where(['contact_id' => $this->id])->orderBy(['id' => SORT_DESC])->limit(50)->all();
    }

    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
