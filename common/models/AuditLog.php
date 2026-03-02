<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "audit_log".
 *
 * @property int $id
 * @property int $user_id
 * @property string $action
 * @property int $entity_id
 * @property string $occurred_at
 *
 * @property Entity $entity
 * @property User $user
 */
class AuditLog extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'audit_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'action', 'entity_id'], 'required'],
            [['user_id', 'entity_id'], 'integer'],
            [['occurred_at'], 'safe'],
            [['action'], 'string', 'max' => 10],
            [['entity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Entity::class, 'targetAttribute' => ['entity_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'action' => 'Action',
            'entity_id' => 'Entity ID',
            'occurred_at' => 'Occurred At',
        ];
    }

    /**
     * Gets query for [[Entity]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEntity()
    {
        return $this->hasOne(Entity::class, ['id' => 'entity_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
