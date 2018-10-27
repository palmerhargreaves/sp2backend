<?php

namespace common\models\dealers;

use Yii;

/**
 * This is the model class for table "dealers".
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property integer $number
 * @property string $address
 * @property string $phone
 * @property string $site
 * @property string $email
 * @property string $email_so
 * @property string $longitude
 * @property string $latitude
 * @property string $image
 * @property integer $city_id
 * @property integer $importer_id
 * @property integer $company_id
 * @property integer $regional_manager_id
 * @property integer $nfz_regional_manager_id
 * @property integer $latest_update_id
 * @property integer $dealer_type
 * @property integer $status
 * @property integer $shop_id
 * @property integer $shop_sc_id
 * @property string $shop_password
 * @property integer $dealer_group_id
 */
class Dealers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dealers';
    }

    public static function getDb ()
    {
        return Yii::$app->get('db2');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number', 'city_id', 'importer_id', 'company_id', 'regional_manager_id', 'nfz_regional_manager_id', 'latest_update_id', 'dealer_type', 'status', 'shop_id', 'shop_sc_id', 'dealer_group_id'], 'integer'],
            [['longitude', 'latitude'], 'number'],
            [['name'], 'string', 'max' => 60],
            [['slug', 'email_so'], 'string', 'max' => 50],
            [['address', 'phone', 'image'], 'string', 'max' => 255],
            [['site', 'email'], 'string', 'max' => 128],
            [['shop_password'], 'string', 'max' => 20],
            [['number'], 'unique'],
            [['slug'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'number' => 'Number',
            'address' => 'Address',
            'phone' => 'Phone',
            'site' => 'Site',
            'email' => 'Email',
            'email_so' => 'Email So',
            'longitude' => 'Longitude',
            'latitude' => 'Latitude',
            'image' => 'Image',
            'city_id' => 'City ID',
            'importer_id' => 'Importer ID',
            'company_id' => 'Company ID',
            'regional_manager_id' => 'Regional Manager ID',
            'nfz_regional_manager_id' => 'Nfz Regional Manager ID',
            'latest_update_id' => 'Latest Update ID',
            'dealer_type' => 'Dealer Type',
            'status' => 'Status',
            'shop_id' => 'Shop ID',
            'shop_sc_id' => 'Shop Sc ID',
            'shop_password' => 'Shop Password',
            'dealer_group_id' => 'Dealer Group ID',
            'dealer_id' => 'Номер дилера'
        ];
    }

    public function getShortNumber() {
        return substr($this->number, -3);
    }

    public function getDealerTypeLabel() {
        $types = array(
            1 => 'PKW',
            2 => 'NFZ',
            3 => 'PKW+NFZ'
        );

        return array_key_exists($this->dealer_type, $types) ? $types[$this->dealer_type] : 'Нет';
    }
}
