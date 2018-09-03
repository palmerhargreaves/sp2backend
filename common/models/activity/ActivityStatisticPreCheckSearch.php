<?php

namespace common\models\activity;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "activity_statistic_pre_check".
 *
 * @property integer $id
 * @property integer $activity_id
 * @property integer $statistic_id
 * @property integer $dealer_id
 * @property integer $user_who_check
 * @property integer $quarter
 * @property integer $year
 * @property integer $is_checked
 * @property string $created_at
 * @property string $updated_at
 */
class ActivityStatisticPreCheckSearch extends ActivityStatisticPreCheck
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ActivityStatisticPreCheck::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        //$query->andFilterWhere(['like', 'name', $this->name]);
        //$query->andFilterWhere(['finished' => false]);

        //$query->orderBy(['position' => SORT_ASC]);

        return $dataProvider;
    }
}
