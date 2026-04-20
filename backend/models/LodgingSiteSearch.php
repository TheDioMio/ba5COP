<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\LodgingSite;

class LodgingSiteSearch extends LodgingSite
{
    public function rules()
    {
        return [
            [['id', 'capacity_total', 'capacity_available'], 'integer'],
            [['name', 'notes'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params, $formName = null)
    {
        $query = LodgingSite::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // filtros
        $query->andFilterWhere([
            'id' => $this->id,
            'capacity_total' => $this->capacity_total,
            'capacity_available' => $this->capacity_available,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'notes', $this->notes]);

        return $dataProvider;
    }
}