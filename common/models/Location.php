<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "location".
 *
 * @property int $id
 * @property int $location_type_id
 * @property string $name
 * @property string|null $notes
 * @property string $geometry
 * @property int $status_type_id
 * @property string $updated_at
 * @property int $entity_id
 *
 * @property Entity $entity
 * @property Incident[] $incidents
 * @property LocationType $locationType
 * @property LodgingSite[] $lodgingSites
 * @property StatusType $statusType
 * @property Task[] $tasks
 */
class Location extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'location';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['notes'], 'default', 'value' => null],
            [['location_type_id', 'name', 'geometry', 'status_type_id', 'entity_id'], 'required'],
            [['location_type_id', 'status_type_id', 'entity_id'], 'integer'],
            [['geometry'], 'string'],
            [['updated_at'], 'safe'],
            [['name'], 'string', 'max' => 25],
            [['notes'], 'string', 'max' => 255],
            [['entity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Entity::class, 'targetAttribute' => ['entity_id' => 'id']],
            [['location_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => LocationType::class, 'targetAttribute' => ['location_type_id' => 'id']],
            [['status_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusType::class, 'targetAttribute' => ['status_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'location_type_id' => 'ID do Tipo de Localização',
            'name' => 'Nome',
            'notes' => 'OBS.',
            'geometry' => 'Data Geometria Mapa',
            'status_type_id' => 'ID do Status',
            'updated_at' => 'Atualizado às',
            'entity_id' => 'ID do Tipo de Entidade',
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
     * Gets query for [[Incidents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIncidents()
    {
        return $this->hasMany(Incident::class, ['location_id' => 'id']);
    }

    /**
     * Gets query for [[LocationType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocationType()
    {
        return $this->hasOne(LocationType::class, ['id' => 'location_type_id']);
    }

    /**
     * Gets query for [[LodgingSites]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLodgingSites()
    {
        return $this->hasMany(LodgingSite::class, ['location_id' => 'id']);
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
        return $this->hasMany(Task::class, ['location_id' => 'id']);
    }

    /**
     * Lista de localizações para dropdown
     * [id => name]
     */
    public static function dropDown(): array
    {
        $rows = self::find()
            ->select(['id', 'name'])
            ->orderBy(['name' => SORT_ASC])
            ->asArray()
            ->all();

        return ArrayHelper::map($rows, 'id', 'name');
    }
}
