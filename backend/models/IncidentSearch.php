<?php

namespace app\models;

use common\models\StatusType;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Incident;

class IncidentSearch extends Incident
{
    public $task_title;
    public $assigned_to;
    public $location_name;
    public $tasks_count;
    public $open_tasks_count;

    public function rules()
    {
        return [
            [['id', 'location_id', 'incident_type_id', 'priority_id', 'status_type_id', 'reported_by', 'entity_id'], 'integer'],
            [['title', 'description', 'task_title', 'location_name'], 'safe'],
            [['assigned_to', 'tasks_count', 'open_tasks_count'], 'integer'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params, $formName = null)
    {
        $query = Incident::find()
            ->alias('i')
            ->joinWith([
                'location l',
                'tasks t',
                'incidentType it',
                'priority p',
                'statusType st',
            ]);

        $query->select([
            'i.*',
            'tasks_count' => 'COUNT(DISTINCT t.id)',
            'open_tasks_count' => 'COUNT(DISTINCT CASE WHEN t.status_type_id != ' . StatusType::STATUS_TASK_DONE . ' THEN t.id END)',
        ]);

        $query->groupBy('i.id');

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
                    'incident_type_id' => [
                        'asc' => ['it.description' => SORT_ASC],
                        'desc' => ['it.description' => SORT_DESC],
                    ],
                    'priority_id' => [
                        'asc' => ['p.id' => SORT_ASC],
                        'desc' => ['p.id' => SORT_DESC],
                    ],
                    'status_type_id' => [
                        'asc' => ['st.description' => SORT_ASC],
                        'desc' => ['st.description' => SORT_DESC],
                    ],
                    'location_name' => [
                        'asc' => ['l.name' => SORT_ASC],
                        'desc' => ['l.name' => SORT_DESC],
                    ],
                    'task_title' => [
                        'asc' => ['t.title' => SORT_ASC],
                        'desc' => ['t.title' => SORT_DESC],
                    ],
                    'assigned_to' => [
                        'asc' => ['t.assigned_to' => SORT_ASC],
                        'desc' => ['t.assigned_to' => SORT_DESC],
                    ],

                    // NOVOS CAMPOS ORDENÁVEIS
                    'tasks_count' => [
                        'asc' => ['tasks_count' => SORT_ASC],
                        'desc' => ['tasks_count' => SORT_DESC],
                    ],
                    'open_tasks_count' => [
                        'asc' => ['open_tasks_count' => SORT_ASC],
                        'desc' => ['open_tasks_count' => SORT_DESC],
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
            'i.id' => $this->id,
            'i.location_id' => $this->location_id,
            'i.incident_type_id' => $this->incident_type_id,
            'i.priority_id' => $this->priority_id,
            'i.status_type_id' => $this->status_type_id,
            'i.reported_by' => $this->reported_by,
            'i.entity_id' => $this->entity_id,
        ]);

        $query
            ->andFilterWhere(['like', 'i.title', $this->title])
            ->andFilterWhere(['like', 'i.description', $this->description])
            ->andFilterWhere(['like', 'l.name', $this->location_name])
            ->andFilterWhere(['like', 't.title', $this->task_title]);

        if (!empty($this->assigned_to)) {
            $query->andWhere(['t.assigned_to' => $this->assigned_to]);
        }

        return $dataProvider;
    }
}