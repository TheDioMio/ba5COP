<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use common\models\Entity;

class EntitySearch extends Entity
{
    public $entity_name;
    public $entity_type_name;

    public function rules()
    {
        return [
            [['id', 'entity_type_id'], 'integer'],
            [['entity_name', 'entity_type_name'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params, $formName = null)
    {
        $query = Entity::find()
            ->alias('e')
            ->joinWith(['entityType et'])
            ->leftJoin(['l' => 'location'], 'l.entity_id = e.id')
            ->leftJoin(['i' => 'incident'], 'i.entity_id = e.id')
            ->leftJoin(['t' => 'task'], 't.entity_id = e.id')
            ->leftJoin(['r' => 'request'], 'r.entity_id = e.id')
            ->leftJoin(['d' => 'decision_log'], 'd.entity_id = e.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => [
                    'id' => [
                        'asc' => ['e.id' => SORT_ASC],
                        'desc' => ['e.id' => SORT_DESC],
                    ],
                    'entity_name' => [
                        'asc' => [
                            'l.name' => SORT_ASC,
                            'i.title' => SORT_ASC,
                            't.title' => SORT_ASC,
                            'r.origin' => SORT_ASC,
                            'd.reason' => SORT_ASC,
                            'e.id' => SORT_ASC,
                        ],
                        'desc' => [
                            'l.name' => SORT_DESC,
                            'i.title' => SORT_DESC,
                            't.title' => SORT_DESC,
                            'r.origin' => SORT_DESC,
                            'd.reason' => SORT_DESC,
                            'e.id' => SORT_DESC,
                        ],
                    ],
                    'entity_type_name' => [
                        'asc' => ['et.name' => SORT_ASC],
                        'desc' => ['et.name' => SORT_DESC],
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
            'e.id' => $this->id,
            'e.entity_type_id' => $this->entity_type_id,
        ]);

        if (!empty($this->entity_name)) {
            $query->andWhere([
                'or',
                ['like', new Expression('CAST(e.id AS CHAR)'), $this->entity_name],
                ['like', 'l.name', $this->entity_name],
                ['like', 'i.title', $this->entity_name],
                ['like', 't.title', $this->entity_name],
                ['like', 'r.origin', $this->entity_name],
                ['like', 'r.details', $this->entity_name],
                ['like', 'd.reason', $this->entity_name],
                ['like', new Expression("CONCAT(et.name, ' #', e.id)"), $this->entity_name],
            ]);
        }

        if (!empty($this->entity_type_name)) {
            $query->andWhere(['et.name' => $this->entity_type_name]);
        }

        return $dataProvider;
    }
}