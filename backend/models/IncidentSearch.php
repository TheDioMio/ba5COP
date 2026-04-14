<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Incident;

/**
 * IncidentSearch represents the model behind the search form of `common\models\Incident`.
 */
class IncidentSearch extends Incident
{
    public $task_title;
    public $assigned_to;
    public $location_name;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'location_id', 'incident_type_id', 'priority_id', 'status_type_id', 'reported_by', 'entity_id'], 'integer'],
            [['title', 'description', 'task_title', 'location_name'], 'safe'],
            [['assigned_to'], 'integer'],
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
    public function search($params, $formName = null) {
        $query = Incident::find()
            ->alias('i')
            ->joinWith(['location l', 'tasks t']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => [
                    'id' => [
                        'asc' => ['i.id' => SORT_ASC],
                        'desc' => ['i.id' => SORT_DESC],
                    ],
                    'title' => [
                        'asc' => ['i.title' => SORT_ASC],
                        'desc' => ['i.title' => SORT_DESC],
                    ],
                    'location_name' => [
                        'asc' => ['l.name' => SORT_ASC],
                        'desc' => ['l.name' => SORT_DESC],
                    ],
                    'incident_type_id' => [
                        'asc' => ['i.incident_type_id' => SORT_ASC],
                        'desc' => ['i.incident_type_id' => SORT_DESC],
                    ],
                    'priority_id' => [
                        'asc' => ['i.priority_id' => SORT_ASC],
                        'desc' => ['i.priority_id' => SORT_DESC],
                    ],
                    'status_type_id' => [
                        'asc' => ['i.status_type_id' => SORT_ASC],
                        'desc' => ['i.status_type_id' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $query->groupBy('i.id');

        $this->load($params, $formName);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'i.id' => $this->id,
            'i.location_id' => $this->location_id,
            'i.incident_type_id' => $this->incident_type_id,
            'i.priority_id' => $this->priority_id,
            'i.status_type_id' => $this->status_type_id,
            'i.reported_by' => $this->reported_by,
            'i.entity_id' => $this->entity_id,
        ]);

        $query->andFilterWhere(['like', 'i.title', $this->title])
            ->andFilterWhere(['like', 'i.description', $this->description])
            ->andFilterWhere(['like', 'l.name', $this->location_name])
            ->andFilterWhere(['like', 't.title', $this->task_title]);

        if (!empty($this->assigned_to)) {
            $query->andWhere(['t.assigned_to' => $this->assigned_to]);
        }

        return $dataProvider;
    }
}
