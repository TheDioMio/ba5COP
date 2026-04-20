<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "lodging_site".
 *
 * @property int $id
 * @property string $name
 * @property int $capacity_total
 * @property int $capacity_available
 * @property string|null $notes
 * @property string|null $geometry
 *
 * @property LodgingEntry[] $lodgingEntries
 */
class LodgingSite extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lodging_site';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['notes', 'geometry'], 'default', 'value' => null],
            [['name', 'capacity_total', 'capacity_available'], 'required'],
            [['capacity_total', 'capacity_available'], 'integer'],
            [['notes', 'geometry'], 'string'],
            [['name'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nome',
            'capacity_total' => 'Capacidade Total',
            'capacity_available' => 'Capacidade Disponível',
            'notes' => 'OBS.',
            'geometry' => 'Geometria',
        ];
    }

    /**
     * Gets query for [[LodgingEntries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLodgingEntries()
    {
        return $this->hasMany(LodgingEntry::class, ['lodging_site_id' => 'id']);
    }

    /**
     * Lista de alojamentos para dropdown
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
     * Devolve o número de camas ocupadas.
     */
    public function occupancy()
    {
        $occupancy = LodgingEntry::find()
            ->where(['lodging_site_id' => $this->id])
            ->andWhere(['checkout_at' => null]) // só pessoas ainda alojadas
            ->sum('people_count');

        return (int)($occupancy ?? 0);
    }

    /**
     * Devolve o número de capacidade atual do alojamento, com uma cor associada.
     * (Capacidade total - capacidade ocupada)
     *
     * O $badge é boolean e determina se devolve só o número ou HTML formatado.
     */
    public function getCurrentCapacity($badge)
    {
        $totalCapacity = (int)$this->capacity_total;
        $occupancy = $this->occupancy();

        $currentCapacity = $totalCapacity - $occupancy;

        if ($badge == true) {
            // percentagem de ocupação/pressão da capacidade
            $divisor = $totalCapacity > 0 ? $totalCapacity : 1;
            $percentageAvailable = ($currentCapacity / $divisor) * 100;

            if ($percentageAvailable <= 0) {
                $color = 'red';
            } elseif ($percentageAvailable < 20) {
                $color = 'orange';
            } else {
                $color = 'green';
            }

            return "<span style='color:$color;font-weight:bold;'>$currentCapacity</span>";
        }

        return $currentCapacity;
    }

    /**
     * Devolve o número total de camas (capacidade total) em todos os alojamentos.
     */
    public static function getOverallCapacity()
    {
        $overallBeds = self::find()->sum('capacity_total');
        return (int)($overallBeds ?? 0);
    }

    /**
     * Devolve o número total de camas disponíveis em todos os alojamentos.
     */
    public static function getOverallAvailability()
    {
        $overallBeds = self::getOverallCapacity();
        $takenBeds = LodgingEntry::getOverallOccupancy();

        return $overallBeds - (int)($takenBeds ?? 0);
    }

    /**
     * Devolve a lista dos alojamentos com camas disponíveis.
     */
    public static function findWithAvailableBeds()
    {
        $sites = self::find()
            ->with(['lodgingEntries'])
            ->all();

        return array_filter($sites, function ($site) {
            return $site->getCurrentCapacity(false) > 0;
        });
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
     * Devolve o alojamento em formato GeoJSON Feature para o mapa.
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
                'entity_type' => 'lodging_site',
                'name' => $this->name,
                'capacity_total' => $this->capacity_total,
                'capacity_available' => $this->capacity_available,
                'notes' => $this->notes,
            ],
        ];
    }
}