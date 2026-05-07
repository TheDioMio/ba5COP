<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Request;

/**
 * RequestSearch represents the model behind the search form of `common\models\Request`.
 */
class RequestSearch extends Request
{
    public ?int $fixedIsExternal = null;
    public ?string $customFormName = null;

    public function formName()
    {
        return $this->customFormName ?: parent::formName();
    }

    public function rules()
    {
        return [
            [[
                'id',
                'is_external',
                'request_type_id',
                'priority_id',
                'status_type_id',
                'entity_id'
            ], 'integer'],

            [['origin', 'details', 'created_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Request::find()
            ->with(['priority', 'statusType', 'requestType']);

        if ($this->fixedIsExternal !== null) {
            $query->andWhere(['is_external' => $this->fixedIsExternal]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'is_external' => $this->is_external,
            'request_type_id' => $this->request_type_id,
            'priority_id' => $this->priority_id,
            'status_type_id' => $this->status_type_id,
            'created_at' => $this->created_at,
            'entity_id' => $this->entity_id,
        ]);

        $query->andFilterWhere(['like', 'origin', $this->origin])
            ->andFilterWhere(['like', 'details', $this->details]);

        return $dataProvider;
    }
}