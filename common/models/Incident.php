<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "incident".
 *
 * @property int $id
 * @property int $location_id
 * @property string $title
 * @property string $description
 * @property int $incident_type_id
 * @property int $priority_id
 * @property int $status_type_id
 * @property int $reported_by
 * @property int $entity_id
 *
 * @property Entity $entity
 * @property IncidentType $incidentType
 * @property Location $location
 * @property Priority $priority
 * @property User $reportedBy
 * @property StatusType $statusType
 * @property Task[] $tasks
 */
class Incident extends \yii\db\ActiveRecord {


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'incident';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['location_id', 'title', 'description', 'incident_type_id', 'priority_id', 'status_type_id', 'reported_by', 'entity_id'], 'required'],
            [['location_id', 'incident_type_id', 'priority_id', 'status_type_id', 'reported_by', 'entity_id'], 'integer'],
            [['title'], 'string', 'max' => 25],
            [['description'], 'string', 'max' => 120],
            [['entity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Entity::class, 'targetAttribute' => ['entity_id' => 'id']],
            [['incident_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => IncidentType::class, 'targetAttribute' => ['incident_type_id' => 'id']],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Location::class, 'targetAttribute' => ['location_id' => 'id']],
            [['priority_id'], 'exist', 'skipOnError' => true, 'targetClass' => Priority::class, 'targetAttribute' => ['priority_id' => 'id']],
            [['status_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusType::class, 'targetAttribute' => ['status_type_id' => 'id']],
            [['reported_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['reported_by' => 'id']],
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
            'title' => 'Título',
            'description' => 'Descrição',
            'incident_type_id' => 'ID do Tipo do Incidente',
            'priority_id' => 'ID da Prioridade',
            'status_type_id' => 'ID do tipo de status',
            'reported_by' => 'Reportado por',
            'entity_id' => 'ID da Entidade',
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
     * Gets query for [[IncidentType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIncidentType()
    {
        return $this->hasOne(IncidentType::class, ['id' => 'incident_type_id']);
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
     * Gets query for [[ReportedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReportedBy()
    {
        return $this->hasOne(User::class, ['id' => 'reported_by']);
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
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::class, ['incident_id' => 'id']);
    }


    /**
     * Devolve o número total de incidentes de X tipo
     * EX. Número total de incidentes por fuga de água.
     *
     * Devolve -1 se não encontrar nada.
     */
    public static function incidentTotal($incidentType){
        if(Incident::find()->where(['incident_type_id' => $incidentType]) != null) {
            $incidentCount = Incident::find()
                ->where(['incident_type_id' => $incidentType])
                ->asArray()
                ->all();
            return $incidentCount;
        } else {
            return -1;
        }
    }

    /**
     * Devolve o número total de incidentes de X tipo PASSADOS
     * EX. Número total de incidentes por fuga de água que já foram concluídos
     *
     * Devolve -1 se não encontrar nada.
     */
    public static function incidentDoneTotal($incidentType){
        if(Incident::find()->where(['incident_type_id' => $incidentType]) != null) {
            $incidentCount = Incident::find()
                ->where(['incident_type_id' => $incidentType])
                ->andWhere(['status_type_id' => StatusType::STATUS_INCIDENT_RESOLVED])
                ->asArray()
                ->all();
            return $incidentCount;
        } else {
            return -1;
        }
    }

    /**
     * Devolve o número total de incidentes de X tipo ATIVOS
     * EX. Número total de incidentes por fuga de água que estão OPEN ou IN_PROGRESS
     *
     * Devolve -1 se não encontrar nada.
     */
    public static function incidentActiveTotal($incidentType){
        if(Incident::find()->where(['incident_type_id' => $incidentType]) != null) {
            $incidentCount = Incident::find()
                ->where(['incident_type_id' => $incidentType])
                ->andWhere(['status_type_id' => [StatusType::STATUS_INCIDENT_OPEN, StatusType::STATUS_INCIDENT_IN_PROGRESS,]])
                ->asArray()
                ->all();
            return $incidentCount;
        } else {
            return -1;
        }
    }

    public static function findActiveByType($incidentType) {
        return self::find()
            ->where(['incident_type_id' => $incidentType])
            ->andWhere([
                'status_type_id' => [
                    StatusType::STATUS_INCIDENT_OPEN,
                    StatusType::STATUS_INCIDENT_IN_PROGRESS,
                ]
            ])
            ->with('location');
    }
}
