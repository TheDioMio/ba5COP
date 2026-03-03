<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Form model para criar/editar utilizadores no backend + RBAC role.
 */
class UserForm extends Model
{
    public ?int $id = null;
    public string $email = '';
    public string $username = '';
    public int $status = 10;

    public ?string $password = null;   // virtual
    public ?string $role_name = null;  // RBAC role name (ex: 'comandante')

    private ?User $_user = null;

    public function rules(): array
    {
        return [
            [['email', 'username', 'status'], 'required'],
            ['email', 'email'],

            [['email', 'username'], 'string', 'max' => 255],
            [['status'], 'integer'],
            [['password'], 'string', 'min' => 6],
            [['role_name'], 'string', 'max' => 64],

            // validações únicas (ignora o próprio no update)
            ['email', 'validateEmailUnique'],
            ['username', 'validateUsernameUnique'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'email' => 'Email',
            'username' => 'Username',
            'password' => 'Password',
            'status' => 'Estado',
            'role_name' => 'Role do Utilizador',
        ];
    }

    public function setUser(?User $user): void
    {
        $this->_user = $user;
        if ($user) {
            $this->id = (int)$user->id;
            $this->email = (string)$user->email;
            $this->username = (string)$user->username;
            $this->status = (int)$user->status;

            //Role atual (1 por user)
            $rolesByUser = Yii::$app->authManager->getRolesByUser($user->id);
            $current = reset($rolesByUser);
            $this->role_name = $current ? $current->name : null;
        }
    }

    public function getUser(): ?User
    {
        return $this->_user;
    }

    public function save(): bool
    {
        $isNew = $this->_user === null;

        $user = $this->_user ?? new User();

        $user->email = $this->email;
        $user->username = $this->username;
        $user->status = $this->status;

        if ($isNew) {
            // no create, password obrigatório
            if (empty($this->password)) {
                $this->addError('password', 'Password é obrigatória ao criar utilizador.');
                return false;
            }
            $user->generateAuthKey();
        }

        // no update, só muda se preenchida
        if (!empty($this->password)) {
            $user->setPassword($this->password);
        }

        if (!$user->save()) {
            foreach ($user->errors as $attr => $errors) {
                $this->addError($attr, implode(' ', $errors));
            }
            return false;
        }

        // RBAC: 1 role por user
        $auth = Yii::$app->authManager;
        $auth->revokeAll($user->id);

        if (!empty($this->role_name)) {
            $role = $auth->getRole($this->role_name);
            if ($role) {
                $auth->assign($role, $user->id);
            } else {
                $this->addError('role_name', 'Role inválido.');
                return false;
            }
        }

        // atualiza referência interna
        $this->_user = $user;
        $this->id = (int)$user->id;

        return true;
    }

    public function validateEmailUnique($attribute): void
    {
        $query = User::find()->andWhere(['email' => $this->email]);
        if ($this->id) {
            $query->andWhere(['<>', 'id', $this->id]);
        }
        if ($query->exists()) {
            $this->addError($attribute, 'Este email já está em uso.');
        }
    }

    public function validateUsernameUnique($attribute): void
    {
        $query = User::find()->andWhere(['username' => $this->username]);
        if ($this->id) {
            $query->andWhere(['<>', 'id', $this->id]);
        }
        if ($query->exists()) {
            $this->addError($attribute, 'Este username já está em uso.');
        }
    }
}