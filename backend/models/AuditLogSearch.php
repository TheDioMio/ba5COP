<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AuditLog;

class AuditLogSearch extends AuditLog
{
    public $user_username;
    public $entity_name;

    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['entity_id', 'action', 'occurred_at', 'user_username', 'entity_name'], 'safe'],
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
            ]);

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
                        'asc' => ['et.name' => SORT_ASC],
                        'desc' => ['et.name' => SORT_DESC],
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
            'al.occurred_at' => $this->occurred_at,
        ]);

        $query->andFilterWhere(['like', 'al.action', $this->action]);

        if (!empty($this->user_username)) {
            $query->andWhere(['al.user_id' => $this->user_username]);
        }

        if (!empty($this->entity_name)) {
            $query->andWhere([
                'or',
                ['like', 'et.name', $this->entity_name],
                ['like', 'e.id', $this->entity_name],
                ['like', 'al.entity_id', $this->entity_name],
            ]);
        }

        return $dataProvider;
    }
}