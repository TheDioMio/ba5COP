<?php

namespace common\models;

use Yii;
use yii\db\Query;
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
 * @property int $is_critical
 *
 * @property Entity $entity
 * @property Incident[] $incidents
 * @property LocationType $locationType
 * @property StatusType $statusType
 * @property Task[] $tasks
 */
class Location extends \yii\db\ActiveRecord
{
    const BUILDING = 'BUILDING';
    const AREA = 'AREA';
    const POINT = 'POINT';
    const ROAD = 'ROAD';
    const VEDACAO = 'VEDACAO';
    const PARKING = 'PARKING';

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
            [['is_critical'], 'default', 'value' => 0],
            [['location_type_id', 'name', 'geometry', 'status_type_id', 'entity_id'], 'required'],
            [['location_type_id', 'status_type_id', 'entity_id', 'is_critical'], 'integer'],
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
            'is_critical' => 'Zona Crítica',
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

    /**
     * Devolve a geometria descodificada em array.
     */
    public function getGeometryArray(): ?array
    {
        if (empty($this->geometry)) {
            return null;
        }

        $decoded = json_decode($this->geometry, true);

        return is_array($decoded) ? $decoded : null;
    }

    /**
     * Devolve a localização em formato GeoJSON Feature para o mapa.
     */
    public function toGeoJsonFeature(): ?array
    {
        $geometry = $this->getGeometryArray();

        if ($geometry === null) {
            return null;
        }

        return [
            'type' => 'Feature',
            'id' => $this->id,
            'geometry' => $geometry,
            'properties' => [
                'entity_type' => 'location',
                'name' => $this->name,
                'location_type_id' => $this->location_type_id,
                'location_type_name' => $this->locationType->description ?? null,
                'status_type_id' => $this->status_type_id,
                'status_type_name' => $this->statusType->description ?? null,
                'notes' => $this->notes,
                'is_critical' => (int)$this->is_critical,
            ],
        ];
    }

    /**
     * Lista total de vedações na base
     * Devolve int (número total)
     */
    public static function getPerimeterTotal(): int
    {
        return (int)(new Query())
            ->from(['l' => 'location'])
            ->innerJoin(['lt' => 'location_type'], 'lt.id = l.location_type_id')
            ->where(['lt.description' => 'VEDACAO'])
            ->count();
    }

    /**
     * Lista total de vedações na base POR STATUS
     * Devolve int (número total do status em questão)
     */
    public static function getPerimeterTotalByStatus(string $status): int
    {
        return (int)(new Query())
            ->from(['l' => 'location'])
            ->innerJoin(['lt' => 'location_type'], 'lt.id = l.location_type_id')
            ->innerJoin(['st' => 'status_type'], 'st.id = l.status_type_id')
            ->where([
                'lt.description' => 'VEDACAO',
                'st.description' => strtoupper($status),
            ])
            ->count();
    }

    /**
     * Percentagem do perímetro operacional
     */
    public static function getPerimeterOperationalPercentage()
    {
        $total = self::getPerimeterTotal();
        $green = self::getPerimeterTotalByStatus('GREEN');

        if ($total === 0) {
            return 0;
        }

        return (int)round(($green / $total) * 100);
    }

    /**
     * Query dos perímetros INOPs
     */
    public static function findPerimeterInop()
    {
        return self::find()
            ->from(['l' => 'location'])
            ->innerJoin(['lt' => 'location_type'], 'lt.id = l.location_type_id')
            ->where(['lt.description' => self::VEDACAO])
            ->andWhere(['!=', 'l.status_type_id', 1]);
    }

    /**
     * Total de CORREDORES CRÍTICOS
     */
    public static function getCriticalCorridorsTotal(): int
    {
        return (int)(new Query())
            ->from(['l' => 'location'])
            ->innerJoin(['lt' => 'location_type'], 'lt.id = l.location_type_id')
            ->where([
                'l.is_critical' => 1,
                'lt.description' => self::ROAD,
            ])
            ->count();
    }

    /**
     * Total de CORREDORES CRÍTICOS ABERTOS
     * Se levar parâmetro, devolve os CORREDORES CRÍTICOS por STATUS.
     */
    public static function getCriticalCorridors(?string $status = null)
    {
        $query = (new Query())
            ->from(['l' => 'location'])
            ->innerJoin(['lt' => 'location_type'], 'lt.id = l.location_type_id')
            ->where([
                'l.is_critical' => 1,
                'lt.description' => self::ROAD,
            ]);

        if ($status !== null) {
            $status = strtoupper($status);

            if (!in_array($status, ['GREEN', 'YELLOW', 'RED'], true)) {
                return 0;
            }

            $query->innerJoin(['st' => 'status_type'], 'st.id = l.status_type_id')
                ->andWhere(['st.description' => $status]);
        }

        return (int)$query->count();
    }

    /**
     * Total de ESTACIONAMENTOS CRÍTICOS ABERTOS
     * Se levar parâmetro, devolve os ESTACIONAMENTOS CRÍTICOS por STATUS.
     */
    public static function getCriticalParkings(?string $status = null)
    {
        $query = (new Query())
            ->from(['l' => 'location'])
            ->innerJoin(['lt' => 'location_type'], 'lt.id = l.location_type_id')
            ->where([
                'l.is_critical' => 1,
                'lt.description' => self::PARKING,
            ]);

        if ($status !== null) {
            $status = strtoupper($status);

            if (!in_array($status, ['GREEN', 'YELLOW', 'RED'], true)) {
                return 0;
            }

            $query->innerJoin(['st' => 'status_type'], 'st.id = l.status_type_id')
                ->andWhere(['st.description' => $status]);
        }

        return (int)$query->count();
    }

    /**
     * Query de estacionamentos totais que são CRÍTICOS
     */
    public static function findCriticalParkings()
    {
        return self::find()
            ->from(['l' => 'location'])
            ->innerJoin(['lt' => 'location_type'], 'lt.id = l.location_type_id')
            ->where([
                'l.is_critical' => 1,
                'lt.description' => self::PARKING,
            ]);
    }

    /**
     * Query de estradas totais que são CRÍTICAS
     */
    public static function findCriticalRoads()
    {
        return self::find()
            ->from(['l' => 'location'])
            ->innerJoin(['lt' => 'location_type'], 'lt.id = l.location_type_id')
            ->where([
                'l.is_critical' => 1,
                'lt.description' => self::ROAD,
            ]);
    }

    /**
     * Array de todas as localizações com X tipo de location_type_id
     */
    public static function getLocationsOfType($location_type)
    {
        return self::find()
            ->where(['location_type_id' => $location_type])
            ->with('statusType')
            ->orderBy(['name' => SORT_ASC])
            ->all();
    }
}