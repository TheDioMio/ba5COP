<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\LodgingEntry;

/**
 * LodgingEntrySearch represents the model behind the search form of `common\models\LodgingEntry`.
 */
class LodgingEntrySearch extends LodgingEntry
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'lodging_site_id', 'branch_id', 'people_count'], 'integer'],
            [['checkin_at', 'checkout_at', 'notes'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = LodgingEntry::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'lodging_site_id' => $this->lodging_site_id,
            'branch_id' => $this->branch_id,
            'people_count' => $this->people_count,
            'checkin_at' => $this->checkin_at,
            'checkout_at' => $this->checkout_at,
        ]);

        $query->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }
}
