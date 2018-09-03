<?php

namespace common\models\activity\steps;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Categories;

/**
 * CategoriesSearch represents the model behind the search form about `common\models\Categories`.
 */
class ActivityExtendedStatisticStepsSearch extends ActivityExtendedStatisticSteps
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'activity_id'], 'integer'],
            [['header', 'description'], 'safe'],
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
        $query = ActivityExtendedStatisticSteps::find();

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
            'activity_id' => $this->activity_id,
        ]);

        if (\Yii::$app->request->get('id')) {
            $query->andFilterWhere(['activity_id' => \Yii::$app->request->get('id')]);
        }

        $query->andFilterWhere(['like', 'header', $this->header]);
        $query->orderBy(['position' => SORT_ASC]);

        return $dataProvider;
    }
}
