<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use common\models\AuditLog;

class AuditLogSearch extends AuditLog
{
    public $user_username;
    public $entity_name;
    public $occurred_date;
    public $occurred_time;

    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['entity_id', 'action', 'occurred_at', 'user_username', 'entity_name', 'occurred_date', 'occurred_time'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params, $formName = null)
    {
        $query = AuditLog::find()
            ->alias('al')
            ->joinWith([
                'user u',
                'entity e',
                'entity.entityType et',
            ])
            ->leftJoin(['l' => 'location'], 'l.entity_id = e.id')
            ->leftJoin(['i' => 'incident'], 'i.entity_id = e.id')
            ->leftJoin(['t' => 'task'], 't.entity_id = e.id')
            ->leftJoin(['r' => 'request'], 'r.entity_id = e.id')
            ->leftJoin(['d' => 'decision_log'], 'd.entity_id = e.id');

        $entityNameExpression = new Expression("
        COALESCE(
            l.name,
            i.title,
            t.title,
            r.origin,
            d.reason,
            CONCAT(et.name, ' #', e.id)
        )
    ");

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes' => [
                    'id' => [
                        'asc' => ['al.id' => SORT_ASC],
                        'desc' => ['al.id' => SORT_DESC],
                    ],
                    'user_username' => [
                        'asc' => ['u.username' => SORT_ASC],
                        'desc' => ['u.username' => SORT_DESC],
                    ],
                    'entity_name' => [
                        'asc' => [
                            'et.name' => SORT_ASC,
                            'l.name' => SORT_ASC,
                            'i.title' => SORT_ASC,
                            't.title' => SORT_ASC,
                            'r.origin' => SORT_ASC,
                            'd.reason' => SORT_ASC,
                            'e.id' => SORT_ASC,
                        ],
                        'desc' => [
                            'et.name' => SORT_DESC,
                            'l.name' => SORT_DESC,
                            'i.title' => SORT_DESC,
                            't.title' => SORT_DESC,
                            'r.origin' => SORT_DESC,
                            'd.reason' => SORT_DESC,
                            'e.id' => SORT_DESC,
                        ],
                    ],
                    'action' => [
                        'asc' => ['al.action' => SORT_ASC],
                        'desc' => ['al.action' => SORT_DESC],
                    ],
                    'occurred_at' => [
                        'asc' => ['al.occurred_at' => SORT_ASC],
                        'desc' => ['al.occurred_at' => SORT_DESC],
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
            'al.id' => $this->id,
            'al.user_id' => $this->user_id,
        ]);

        if (!empty($this->occurred_date)) {
            $query->andWhere([
                'like',
                'al.occurred_at',
                $this->occurred_date
            ]);
        }

        if (!empty($this->occurred_time)) {
            $query->andWhere([
                'like',
                new Expression("DATE_FORMAT(al.occurred_at, '%H:%i')"),
                $this->occurred_time
            ]);
        }

        $query->andFilterWhere(['like', 'al.action', $this->action]);

        if (!empty($this->user_username)) {
            $query->andWhere(['al.user_id' => $this->user_username]);
        }

        if (!empty($this->entity_name)) {
            $query->andWhere([
                'or',
                ['like', new Expression('CAST(e.id AS CHAR)'), $this->entity_name],
                ['like', new Expression('CAST(al.entity_id AS CHAR)'), $this->entity_name],
                ['like', 'et.name', $this->entity_name],

                // nomes reais das entidades
                ['like', 'l.name', $this->entity_name],
                ['like', 'i.title', $this->entity_name],
                ['like', 't.title', $this->entity_name],
                ['like', 'r.origin', $this->entity_name],
                ['like', 'r.details', $this->entity_name],
                ['like', 'd.reason', $this->entity_name],

                // fallback tipo "LOCATION #10000"
                ['like', new Expression("CONCAT(et.name, ' #', e.id)"), $this->entity_name],
            ]);
        }

        return $dataProvider;
    }
}