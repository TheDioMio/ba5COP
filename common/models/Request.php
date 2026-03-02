<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "request".
 *
 * @property int $id
 * @property int $is_external
 * @property string $origin
 * @property string $details
 * @property int $priority_id
 * @property int $status
 * @property string $created_at
 * @property int $entity_id
 *
 * @property Entity $entity
 * @property Priority $priority
 * @property StatusType $status0
 */
class Request extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_external', 'origin', 'details', 'priority_id', 'status', 'entity_id'], 'required'],
            [['is_external', 'priority_id', 'status', 'entity_id'], 'integer'],
            [['created_at'], 'safe'],
            [['origin'], 'string', 'max' => 30],
            [['details'], 'string', 'max' => 120],
            [['entity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Entity::class, 'targetAttribute' => ['entity_id' => 'id']],
            [['priority_id'], 'exist', 'skipOnError' => true, 'targetClass' => Priority::class, 'targetAttribute' => ['priority_id' => 'id']],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => StatusType::class, 'targetAttribute' => ['status' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'is_external' => 'Is External',
            'origin' => 'Origin',
            'details' => 'Details',
            'priority_id' => 'Priority ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'entity_id' => 'Entity ID',
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
     * Gets query for [[Priority]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPriority()
    {
        return $this->hasOne(Priority::class, ['id' => 'priority_id']);
    }

    /**
     * Gets query for [[Status0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(StatusType::class, ['id' => 'status']);
    }

}
