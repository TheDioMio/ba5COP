<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

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
class StatusType extends \yii\db\ActiveRecord {
    public const STATUS_GREEN = 1;
    public const STATUS_YELLOW = 2;
    public const STATUS_RED = 3;


//-----------------------------------------------------\\
    public const STATUS_INCIDENT_OPEN = 4;
    public const STATUS_INCIDENT_IN_PROGRESS = 5;
    public const STATUS_INCIDENT_RESOLVED = 6;
//-----------------------------------------------------\\
    public const STATUS_REQUEST_NEW = 11;
    public const STATUS_REQUEST_APPROVED = 12;
    public const STATUS_REQUEST_REJECTED = 13;
    public const STATUS_REQUEST_IN_PROGRESS = 14;
    public const STATUS_REQUEST_DONE = 15;
//-----------------------------------------------------\\
    public const STATUS_TASK_NEW = 7;
    public const STATUS_TASK_DOING = 8;
    public const STATUS_TASK_BLOCKED = 9;
    public const STATUS_TASK_DONE = 10;
//-----------------------------------------------------\\
    public const STATUS_DECISION_BEING_FOLLOWED = 17;
    public const STATUS_DECISION_CANCELLED = 18;


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


    /**
     * Vai buscar o tipo de status, dependendo da entidade que é pedida e passa para dropdown.
     * [id => description] 560 218
     */
    public static function getStatusDropdown($entity)
    {
        $dropDown = StatusType::find()->select(['id', 'description'])->where(['entity_type_id' => $entity])
            ->orderBy(['id'=> SORT_ASC])->asArray()->all();

        return ArrayHelper::map($dropDown, 'id', 'description');
    }

    public static function getStatusLabels($statusID) {
        $label = '';
        switch ($statusID):
            case (self::STATUS_GREEN):
            case (self::STATUS_TASK_DONE):
            case (self::STATUS_INCIDENT_RESOLVED):
            case (self::STATUS_REQUEST_DONE):
            case (self::STATUS_REQUEST_APPROVED):
                return $label = 'badge bg-success';

            case (self::STATUS_YELLOW):
            case (self::STATUS_TASK_DOING):
            case (self::STATUS_INCIDENT_IN_PROGRESS):
                return $label = 'badge bg-warning';

            case (self::STATUS_RED):
            case (self::STATUS_REQUEST_REJECTED):
                return $label = 'badge bg-danger';

            case (self::STATUS_TASK_NEW):
            case (self::STATUS_INCIDENT_OPEN):
            case (self::STATUS_REQUEST_NEW):
                return $label = 'badge bg-primary';

            default:
                return $label = 'badge bg-light';
        endswitch;
    }
}
