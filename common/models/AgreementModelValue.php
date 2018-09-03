<?php

namespace common\models;
use common\models\agreement_model\AgreementModelField;
use DateTime;

/**
 * This is the model class for table "agreement_model_value".
 *
 * @property integer $id
 * @property integer $model_id
 * @property integer $field_id
 * @property string $value
 */
class AgreementModelValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agreement_model_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id', 'field_id'], 'required'],
            [['model_id', 'field_id'], 'integer'],
            [['value'], 'string', 'max' => 255],
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
            'field_id' => 'Field ID',
            'value' => 'Value',
        ];
    }

    /**
     * Get period value from model
     * @param $model
     * @return array|bool|null|string
     */
    public static function getPeriodValueFromModel($model)
    {
        $values = $model->values;

        //Проверка периода в новых категориях
        if ($model->model_category_id != 0 && !empty($model->period)) {
            $value = $model->period;
        }

        if (!empty($value)) {
            return self::getValueDate($value);
        }

        foreach ($values as $key => $value_data) {
            $value = '';

            $field = AgreementModelField::find()->where(['id' => $value_data->field_id])->one();
            if ($field && $field->isPeriodField()) {
                $value = $value_data->value;
            }

            if (empty($value)) {
                return false;
            }

            return self::getValueDate($value);
        }

        return false;
    }

    /**
     * @param $value
     * @return array|null|string
     */
    private static function getValueDate($value)
    {
        $value = explode('-', $value);
        if (!empty($value[1])) {
            $value = $value[1];

            $value = explode('.', $value);
            $dt = DateTime::createFromFormat('Y', '20' . $value[2]);

            if ($dt) {
                $value = sprintf('%s-%s-%s', $value[0], $value[1], $dt->format('Y'));
            } else {
                $value = '';
            }
        } else {
            return false;
        }

        return $value;
    }
}
