<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "entity".
 *
 * @property int $id
 * @property int $entity_type_id
 *
 * @property AuditLog[] $auditLogs
 * @property DecisionLog[] $decisionLogs
 * @property EntityUpdate[] $entityUpdates
 * @property Incident[] $incidents
 * @property Location[] $locations
 * @property Request[] $requests
 * @property Task[] $tasks
 */
class Entity extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entity_type_id'], 'required'],
            [['entity_type_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entity_type_id' => 'Entity Type ID',
        ];
    }

    /**
     * Gets query for [[AuditLogs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuditLogs()
    {
        return $this->hasMany(AuditLog::class, ['entity_id' => 'id']);
    }

    /**
     * Gets query for [[DecisionLogs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDecisionLogs()
    {
        return $this->hasMany(DecisionLog::class, ['entity_id' => 'id']);
    }

    /**
     * Gets query for [[EntityUpdates]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEntityUpdates()
    {
        return $this->hasMany(EntityUpdate::class, ['entity_id' => 'id']);
    }

    /**
     * Gets query for [[Incidents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIncidents()
    {
        return $this->hasMany(Incident::class, ['entity_id' => 'id']);
    }

    /**
     * Gets query for [[Locations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(Location::class, ['entity_id' => 'id']);
    }

    /**
     * Gets query for [[Requests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Request::class, ['entity_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['entity_id' => 'id']);
    }

}
