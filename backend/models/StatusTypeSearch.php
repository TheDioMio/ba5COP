<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\StatusType;

class StatusTypeSearch extends StatusType
{
    public $entity_name;
    public $status_name;

    public function rules()
    {
        return [
            [['id', 'entity_type_id'], 'integer'],
            [['description', 'entity_name', 'status_name'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params, $formName = null)
    {
        $query = StatusType::find()
            ->alias('st')
            ->joinWith(['entityType et']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => [
                    'id' => [
                        'asc' => ['st.id' => SORT_ASC],
                        'desc' => ['st.id' => SORT_DESC],
                    ],
                    'entity_name' => [
                        'asc' => ['et.name' => SORT_ASC],
                        'desc' => ['et.name' => SORT_DESC],
                    ],
                    'status_name' => [
                        'asc' => ['st.description' => SORT_ASC],
                        'desc' => ['st.description' => SORT_DESC],
                    ],
                    'description' => [
                        'asc' => ['st.description' => SORT_ASC],
                        'desc' => ['st.description' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'st.id' => $this->id,
            'st.entity_type_id' => $this->entity_type_id,
        ]);

        if (!empty($this->entity_name)) {
            $query->andWhere(['et.name' => $this->entity_name]);
        }

        if (!empty($this->status_name)) {
            $query->andFilterWhere(['like', 'st.description', $this->status_name]);
        }

        return $dataProvider;
    }
}