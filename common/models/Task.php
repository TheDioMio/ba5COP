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
class Task extends \yii\db\ActiveRecord
{


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
            [['location_id', 'incident_id', 'title', 'description', 'priority_id', 'status_type_id', 'assigned_to', 'created_by', 'due_at', 'entity_id'], 'required'],
            [['location_id', 'incident_id', 'priority_id', 'status_type_id', 'assigned_to', 'created_by', 'entity_id'], 'integer'],
            [['created_at', 'due_at'], 'safe'],
            [['title'], 'string', 'max' => 20],
            [['description'], 'string', 'max' => 120],
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
            'location_id' => 'Location ID',
            'incident_id' => 'Incident ID',
            'title' => 'Title',
            'description' => 'Description',
            'priority_id' => 'Priority ID',
            'status_type_id' => 'Status Type ID',
            'assigned_to' => 'Assigned To',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'due_at' => 'Due At',
            'entity_id' => 'Entity ID',
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

}
