<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\DecisionLog;
use yii\db\Expression;

/**
 * DecisionLogSearch represents the model behind the search form of `common\models\DecisionLog`.
 */
class DecisionLogSearch extends DecisionLog
{
    public $decided_date;
    public $decided_time;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'decided_by', 'entity_id'], 'integer'],
            [['reason', 'decided_at', 'decided_date', 'decided_time'], 'safe'],
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
        $query = DecisionLog::find();

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
            'decided_by' => $this->decided_by,
            'entity_id' => $this->entity_id,
        ]);

        if (!empty($this->decided_date)) {
            $query->andWhere([
                'like',
                'decided_at',
                $this->decided_date
            ]);
        }

        if (!empty($this->decided_time)) {
            $query->andWhere([
                'like',
                new Expression("DATE_FORMAT(decided_at, '%H:%i')"),
                $this->decided_time
            ]);
        }

        $query->andFilterWhere(['like', 'reason', $this->reason]);

        return $dataProvider;
    }
}
