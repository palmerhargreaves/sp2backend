<?php

namespace common\models\activity;

use backend\components\helpers\FilesHelper;
use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "activity_type_company_images".
 *
 * @property integer $id
 * @property integer $activity_id
 * @property integer $company_type_id
 * @property string $path
 */
class ActivityTypeCompanyImages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'activity_type_company_images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_id', 'company_type_id'], 'required'],
            [['id', 'activity_id', 'company_type_id'], 'integer'],
            [['path'], 'string', 'max' => 255],
            [['path'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpeg, jpg, gif'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_id' => 'Activity ID',
            'company_type_id' => 'Company Type ID',
            'path' => 'Изображение',
        ];
    }

    public function upload() {
        $uploaded_file = UploadedFile::getInstance($this, 'path');

        if (is_null($uploaded_file)) {
            return false;
        }

        $dest_path = Yii::$app->params['basePathImages'].'company_types/';
        $gen_file_helper = new FilesHelper($dest_path);
        $gen_file_name = $gen_file_helper->generate($uploaded_file->name);

        if ($uploaded_file->saveAs($dest_path . $gen_file_name)) {
            $this->path= $gen_file_name;
            $this->save(false);
        }

        return $this->save(false);
    }
}
