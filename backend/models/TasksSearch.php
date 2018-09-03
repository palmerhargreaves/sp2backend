<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Tasks;
use common\utils\D;

/**
 * TasksSearch represents the model behind the search form about `common\models\Tasks`.
 */
class TasksSearch extends Tasks
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'owner_id'], 'integer'],
            [['created_at', 'updated_at', 'reserved_to'], 'datetime'],
            [['number', 'title', 'description', 'work_status'], 'safe'],
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
        $query = Tasks::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
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
            'owner_id' => $this->owner_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'reserved_to' => $this->reserved_to,
        ]);

        if (isset($params['year']) && isset($params['month'])) {
            if (!isset($params['day'])) {
                $params['day'] = date('d');
            }
            yii::$app->session->set('tasks.filter_by_day', $params['day']);

            yii::$app->session->set('tasks.filter_by_year', $params['year']);
            yii::$app->session->set('tasks.filter_by_month', $params['month']);

            $query->andFilterWhere(['like', 'created_at', D::makeShortDate($params['year'], $params['month'], isset($params['day']) ? $params['day'] : null)]);
        } else if(yii::$app->session->get('tasks.filter_by_year')) {
            $query->andFilterWhere(['like', 'created_at',
                D::makeShortDate(yii::$app->session->get('tasks.filter_by_year'), yii::$app->session->get('tasks.filter_by_month'), yii::$app->session->get('tasks.filter_by_day'))
                ]
            );
        }

        $query->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'work_status', $this->work_status]);

        return $dataProvider;
    }
}
