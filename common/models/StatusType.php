<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "status_type".
 *
 * @property int $id
 * @property int $entity_type_id
 * @property string $description
 *
 * @property EntityType $entityType
 * @property Incident[] $incidents
 * @property Location[] $locations
 * @property Request[] $requests
 * @property Task[] $tasks
 */
class StatusType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'status_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entity_type_id', 'description'], 'required'],
            [['entity_type_id'], 'integer'],
            [['description'], 'string', 'max' => 10],
            [['entity_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => EntityType::class, 'targetAttribute' => ['entity_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entity_type_id' => 'ID do Tipo de Entidade',
            'description' => 'Nome',
        ];
    }

    /**
     * Gets query for [[EntityType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEntityType()
    {
        return $this->hasOne(EntityType::class, ['id' => 'entity_type_id']);
    }

    /**
     * Gets query for [[Incidents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIncidents()
    {
        return $this->hasMany(Incident::class, ['status_type_id' => 'id']);
    }

    /**
     * Gets query for [[Locations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(Location::class, ['status_type_id' => 'id']);
    }

    /**
     * Gets query for [[Requests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Request::class, ['status' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['status_type_id' => 'id']);
    }
}
