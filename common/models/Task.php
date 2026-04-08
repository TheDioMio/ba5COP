<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property int $location_id
 * @property int $incident_id
 * @property string $title
 * @property string $description
 * @property int $priority_id
 * @property int $status_type_id
 * @property int $assigned_to
 * @property int $created_by
 * @property string $created_at
 * @property string $due_at
 * @property int $entity_id
 *
 * @property User $createdBy
 * @property Entity $entity
 * @property Incident $incident
 * @property Location $location
 * @property Priority $priority
 * @property StatusType $statusType
 */
class Task extends \yii\db\ActiveRecord {
    const MAX_PRIORITY = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['block_reason'], 'default', 'value' => null],
            [['location_id', 'incident_id', 'title', 'description', 'priority_id', 'status_type_id', 'assigned_to', 'created_by', 'due_at', 'entity_id'], 'required'],
            [['location_id', 'incident_id', 'priority_id', 'status_type_id', 'assigned_to', 'created_by', 'entity_id'], 'integer'],
            [['created_at', 'due_at'], 'safe'],
            [['title'], 'string', 'max' => 20],
            [['description', 'block_reason'], 'string', 'max' => 120],
            [['entity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Entity::class, 'targetAttribute' => ['entity_id' => 'id']],
            [['incident_id'], 'exist', 'skipOnError' => true, 'targetClass' => Incident::class, 'targetAttribute' => ['incident_id' => 'id']],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Location::class, 'targetAttribute' => ['location_id' => 'id']],
            [['priority_id'], 'exist', 'skipOnError' => true, 'targetClass' => Priority::class, 'targetAttribute' => ['priority_id' => 'id']],
            [['status_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusType::class, 'targetAttribute' => ['status_type_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'location_id' => 'ID da Localização',
            'incident_id' => 'ID do Incidente',
            'title' => 'Título',
            'description' => 'Descrição',
            'priority_id' => 'ID da Prioridade',
            'status_type_id' => 'ID do Status',
            'block_reason' => 'Bloqueio',
            'assigned_to' => 'Entregue a',
            'created_by' => 'Criado por',
            'created_at' => 'Criado às',
            'due_at' => 'Deadline',
            'entity_id' => 'ID da Entidade',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
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
     * Gets query for [[Incident]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIncident()
    {
        return $this->hasOne(Incident::class, ['id' => 'incident_id']);
    }

    /**
     * Gets query for [[Location]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Location::class, ['id' => 'location_id']);
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
     * Gets query for [[StatusType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatusType()
    {
        return $this->hasOne(StatusType::class, ['id' => 'status_type_id']);
    }

    /**
     * Devolve o número total de tarefas CRÍTICAS, independentemente do estado.
     */
    public static function getCriticalTasks() {
        $criticalTasks = self::find()
            ->where(['priority_id' => self::MAX_PRIORITY])
            ->asArray()
            ->all();

        return $criticalTasks;
    }

    /**
     * Devolve a query que é usada por dataproviders, neste caso todas as tarefas CRÍTICAS ativas
     */
    public static function findActiveCritical() {
        return self::find()
            ->where(['priority_id' => self::MAX_PRIORITY])
            ->andWhere([
                'status_type_id' => [
                    StatusType::STATUS_TASK_NEW,
                    StatusType::STATUS_TASK_DOING,
                ]
            ])
            ->with(['priority', 'statusType', 'incident', 'location', 'createdBy']);
    }

    /**
     * Devolve o array total das tarefas CRÍTICAS ATIVAS
     */
    public static function getActiveCriticalTasks() {
        $criticalTasks =  self::find()
            ->where(['priority_id' => self::MAX_PRIORITY])
            ->andWhere([
                'status_type_id' => [
                    StatusType::STATUS_TASK_NEW,
                    StatusType::STATUS_TASK_DOING,
                ]
            ])
            ->asArray()
            ->with(['priority', 'statusType', 'incident', 'location', 'createdBy'])
            ->all();

        return $criticalTasks;
    }

    /**
     * Devolve o array total das tarefas CRÍTICAS FECHADAS
     */
    public static function getClosedCriticalTasks() {
        $criticalTasks =  self::find()
            ->where(['priority_id' => self::MAX_PRIORITY])
            ->andWhere([
                'status_type_id' => [
                    StatusType::STATUS_TASK_DONE
                ]
            ])
            ->asArray()
            ->with(['priority', 'statusType', 'incident', 'location', 'createdBy'])
            ->all();

        return $criticalTasks;
    }

    /**
     * Devolve o array total das últimas 10 tarefas
     */
    public static function getLatest10Tasks() {
        return Task::find()
            ->with(['priority', 'statusType'])
            ->orderBy([
                'created_at' => SORT_DESC
            ])
            ->limit(10)
            ->all();
    }
}
