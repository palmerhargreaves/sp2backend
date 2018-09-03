<?php

namespace common\models\agreement_model;


use yii\base\Model;
use yii\data\ActiveDataProvider;

class AgreementModelSearch extends AgreementModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'model_category_id', 'model_type_id', 'activity_id', 'dealer_id'], 'integer'],
            [['name', 'description', 'cost'], 'safe'],
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
        $query = AgreementModel::find();

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
            'model_category_id' => $this->model_category_id,
            'model_type_id' => $this->model_type_id,
            'activity_id' => $this->activity_id,
            'dealer_id' => $this->dealer_id
        ]);

        $query->andWhere(['like', 'created_at', date('Y-m')]);

        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->orderBy(['id' => SORT_DESC]);
        $query->limit(100);

        return $dataProvider;
    }
}
