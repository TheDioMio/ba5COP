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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_external', 'priority_id', 'status', 'entity_id'], 'integer'],
            [['origin', 'details', 'created_at'], 'safe'],
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
        $query = Request::find()->with(['priority', 'statusType']);

        // add conditions that should always apply here

        if ($this->fixedIsExternal !== null) {
            $query->andWhere(['is_external' => $this->fixedIsExternal]);
        }

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
            'is_external' => $this->is_external,
            'priority_id' => $this->priority_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'entity_id' => $this->entity_id,
        ]);

        $query->andFilterWhere(['like', 'origin', $this->origin])
            ->andFilterWhere(['like', 'details', $this->details]);

        return $dataProvider;
    }
}
