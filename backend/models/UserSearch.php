<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form of `common\models\User`.
 */
class UserSearch extends User {
    public $role_description;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'verification_token', 'role_description'], 'safe'],
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
        $query = User::find()->alias('u');

        // JOIN RBAC (1 role por user)
        $query->leftJoin('{{%auth_assignment}} aa', 'aa.user_id = u.id')
            ->leftJoin('{{%auth_item}} ai', 'ai.name = aa.item_name AND ai.type = 1');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // ordenar por "Role" (description)
        $dataProvider->sort->attributes['role_description'] = [
            'asc' => ['ai.description' => SORT_ASC],
            'desc' => ['ai.description' => SORT_DESC],
        ];

        $this->load($params, $formName);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions (usa alias u.)
        $query->andFilterWhere([
            'u.id' => $this->id,
            'u.status' => $this->status,
            'u.created_at' => $this->created_at,
            'u.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'u.username', $this->username])
            ->andFilterWhere(['like', 'u.auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'u.password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'u.password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'u.email', $this->email])
            ->andFilterWhere(['like', 'u.verification_token', $this->verification_token]);

        // FILTRO por description (nome bonito)
        $query->andFilterWhere(['like', 'ai.description', $this->role_description]);

        // se estiveres a ter duplicados por algum motivo:
        // $query->groupBy('u.id');

        return $dataProvider;
    }
}
