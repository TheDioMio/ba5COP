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
 * @property LodgingSite[] $lodgingSites
 * @property StatusType $statusType
 * @property Task[] $tasks
 */
class Location extends \yii\db\ActiveRecord {
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

    /**
     * Lista total de vedações na base
     * Devolve int (número total)
     */
    public static function getPerimeterTotal(): int {
        return (int) (new Query())
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
        return (int) (new Query())
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
     *
     */
    public static function getPerimeterOperationalPercentage() {
        $total = self::getPerimeterTotal();
        $green = self::getPerimeterTotalByStatus('GREEN');

        if ($total === 0) {
            return 0;
        }

        return (int) round(($green / $total) * 100);
    }

    /**
     * Query dos perímetros INOPs
     *
     */
    public static function findPerimeterInop() {
        return self::find()
            ->from(['l' => 'location'])
            ->innerJoin(['lt' => 'location_type'], 'lt.id = l.location_type_id')
            ->where(['lt.description' => Location::VEDACAO,])
            ->andWhere(['!=', 'l.status_type_id', 1]);
    }

    /**
     * Total de CORREDORES CRÍTICOS
     *
     */
    public static function getCriticalCorridorsTotal(): int
    {
        return (int) (new \yii\db\Query())
            ->from(['l' => 'location'])
            ->innerJoin(['lt' => 'location_type'], 'lt.id = l.location_type_id')
            ->where([
                'l.is_critical' => 1,
                'lt.description' => 'ROAD',
            ])
            ->count();
    }

    /**
     * Total de CORREDORES CRÍTICOS ABERTOS
     * Se levar parâmetro, devolve os CORREDORES CRÍTICOS por STATUS.
     *
     */
    public static function getCriticalCorridors(?string $status = null){
        $query = (new \yii\db\Query())
            ->from(['l' => 'location'])
            ->innerJoin(['lt' => 'location_type'], 'lt.id = l.location_type_id')
            ->where([
                'l.is_critical' => 1,
                'lt.description' => Location::ROAD,
            ]);

        if ($status !== null) {
            $status = strtoupper($status);

            if (!in_array($status, ['GREEN', 'YELLOW', 'RED'], true)) {
                return 0;
            }

            $query->innerJoin(['st' => 'status_type'], 'st.id = l.status_type_id')
                ->andWhere(['st.description' => $status]);
        }

        return (int) $query->count();
    }

    /**
     * Total de ESTACIONAMENTOS CRÍTICOS ABERTOS
     * Se levar parâmetro, devolve os ESTACIONAMENTOS CRÍTICOS por STATUS.
     *
     */
    public static function getCriticalParkings(?string $status = null){
        $query = (new \yii\db\Query())
            ->from(['l' => 'location'])
            ->innerJoin(['lt' => 'location_type'], 'lt.id = l.location_type_id')
            ->where([
                'l.is_critical' => 1,
                'lt.description' => Location::PARKING,
            ]);

        if ($status !== null) {
            $status = strtoupper($status);

            if (!in_array($status, ['GREEN', 'YELLOW', 'RED'], true)) {
                return 0;
            }

            $query->innerJoin(['st' => 'status_type'], 'st.id = l.status_type_id')
                ->andWhere(['st.description' => $status]);
        }

        return (int) $query->count();
    }

    /**
     * Query de estacionamentos totais que são CRÍTICOS
     *
     */
    public static function findCriticalParkings() {
        return self::find()
            ->from(['l' => 'location'])
            ->innerJoin(['lt' => 'location_type'], 'lt.id = l.location_type_id')
            ->where([
                'l.is_critical' => 1,
                'lt.description' => Location::PARKING,
            ]);
    }

    /**
     * Query de estradas totais que são CRÍTICAS
     *
     */
    public static function findCriticalRoads() {
        return self::find()
            ->from(['l' => 'location'])
            ->innerJoin(['lt' => 'location_type'], 'lt.id = l.location_type_id')
            ->where([
                'l.is_critical' => 1,
                'lt.description' => Location::ROAD,
            ]);
    }

}
