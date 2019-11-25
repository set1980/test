<?php

namespace medicine\models\search;

use common\models\queries\ActiveQuery;
use medicine\models\Company;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CompanyPersonSearch represents the model behind the search form about `common\models\CompanyPerson`.
 */
class CompanySearch extends Company
{
    /** @var ActiveQuery */

    public $perPage = 20;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'slug', 'name', 'id_person','person_name','personsCount'], 'safe'],
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

        $query = Company::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['personsCount'=>SORT_ASC]],
            'pagination' => [
                'pageSize' => $this->perPage,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // фильтруем по id
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        // фильтруем по slug
        $query->andFilterWhere([
            'slug' => $this->slug,
        ]);
        // фильтруем по компании
        $query->andFilterWhere(['like', 'name', $this->name]);

        // фильтруем по id сотрудника
        $query->andFilterWhere(['persons.id' => $this->id_person]);

        // фильтруем по имени сотрудника
        $query->andFilterWhere(['like', 'persons.name', $this->person_name]);  

        return $dataProvider;
    }
}

