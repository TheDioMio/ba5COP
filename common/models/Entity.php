<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

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
    const LOCATION_ID = 1;
    const INCIDENT_ID = 2;
    const TASK_ID = 3;
    const REQUEST_ID = 4;


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
     * Lista de entidades para dropdown
     * [id => entity_id]
     */
    public static function dropDown(): array
    {
        $rows = self::find()
            ->select(['id', 'entity_type_id'])
            ->orderBy(['id' => SORT_ASC])
            ->asArray()
            ->all();

        return ArrayHelper::map($rows, 'id', 'id');
    }

    /**
     * Converte de id para nome contreto da entidade
     * [id => task/incident/request.title]
     */
    public function getEntityName()
    {
        if ($task = Task::find()->where(['entity_id' => $this->id])->one()) {
            return $task->title;
        }

        if ($incident = Incident::find()->where(['entity_id' => $this->id])->one()) {
            return $incident->title;
        }

        if ($request = Request::find()->where(['entity_id' => $this->id])->one()) {
            return $request->origin;
        }

        if ($location = Location::find()->where(['entity_id' => $this->id])->one()) {
            return $location->name;
        }

        return null;
    }


    /**
     * Atribui ID's de identidade automaticamente, dependendo de que entidade se trata.
     * EX: Nova REQUEST é criada (começado pelo ID 4XXXX), atribui o próximo ID disponível.
     * [id => task/incident/request.title]
     */

    public static function createEntity(int $entityType): ?self
    {
        $start = match ($entityType) {
            self::LOCATION_ID => 10000,
            self::INCIDENT_ID => 20000,
            self::TASK_ID => 30000,
            self::REQUEST_ID => 40000,
            default => null,
        };

        $end = $start + 9999;

        $max = self::find()
            ->where(['between', 'id', $start, $end])
            ->max('id');

        $entity = new self();
        $entity->id = $max ? ((int)$max + 1) : $start;
        $entity->entity_type_id = $entityType;

        return $entity->save(false) ? $entity : null;
    }
}
