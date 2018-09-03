<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\user\User;

/**
 * UserSearch represents the model behind the search form about `common\models\User`.
 */
class UserSearch extends User
{
    public $name;
    public $role;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['name', 'email', 'phone', 'username', 'role'], 'safe'],
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
        $query = User::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['name'] = [
            'asc' => ['firstname' => SORT_ASC, 'surname' => SORT_ASC],
            'desc' => ['firstname' => SORT_DESC, 'surname' => SORT_DESC],
            'default' => SORT_DESC
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status
        ]);

        $query
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone]);

        $query->andFilterWhere([
            'or',
            ['like', 'username', $this->username],
            ['like', 'firstname', $this->name],
            ['like', 'surname', $this->name],
            ['like', 'CONCAT(firstname, " " , surname)', $this->name],
        ]);

        if($this->role) {
            $query->join('LEFT JOIN', Yii::$app->authManager->assignmentTable . 'as aa', 'aa.user_id = id')
                ->where(['aa.item_name' => $this->role]);
        }

        return $dataProvider;
    }
}
