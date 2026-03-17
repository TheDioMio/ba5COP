<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "lodging_site".
 *
 * @property int $id
 * @property int $location_id
 * @property string $name
 * @property int $capacity_total
 * @property int $capacity_available
 * @property string|null $notes
 *
 * @property Location $location
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
            [['notes'], 'default', 'value' => null],
            [['location_id', 'name', 'capacity_total', 'capacity_available'], 'required'],
            [['location_id', 'capacity_total', 'capacity_available'], 'integer'],
            [['name', 'notes'], 'string', 'max' => 30],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Location::class, 'targetAttribute' => ['location_id' => 'id']],
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
            'name' => 'Nome',
            'capacity_total' => 'Capacidade Total',
            'capacity_available' => 'Capacidade Disponível',
            'notes' => 'OBS.',
        ];
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

    /**d
     *  Devolve o número de camas ocupadas
     *  Query à BD.
     */
    public function occupancy() {
        $occupancy = LodgingEntry::find()
            ->where(['lodging_site_id' => $this->id])
            ->andWhere(['checkout_at' => null]) // só pessoas ainda alojadas
            ->sum('people_count');

        return $occupancy;
    }

    /**
     * Devolve o número de capacidade atual do alojamento, com uma cor associada.
     * (Capacidade total - capacidade ocupada)
     *
     * O $badge é boolean, e determina se manda o número só em int, ou já com formatação de cores.
     */
    public function getCurrentCapacity($badge) {
        $totalCapacity = $this->capacity_total;

        $occupancy = $this->occupancy();

        $currentCapacity = $totalCapacity - $occupancy ?? 0;

        if($badge == true) {
            //Isto aqui evita a divisão por 0, o que daria erro. Ex. 0/300 = Erro, 1/300 = 300.
            if($occupancy == 0){
                $occupancy = 1;
            }

            $percentage = ($totalCapacity / $occupancy) * 100;

            if ($percentage == 0) {
                $color = 'red';
            } elseif ($percentage < 20) {
                $color = 'orange';
            } else {
                $color = 'green';
            }

            return "<span style='color:$color;font-weight:bold;'>$currentCapacity</span>";
        } else {
            return $currentCapacity;
        }
    }

    /**
     * Devolve o número total de camas (capacidade total) em todos os alojamentos.
     *
     */
    public static function getOverallCapacity(){
        $overallBeds = self::find()
            ->sum('capacity_total');

        return $overallBeds;
    }

    public static function getOverallAvailability(){
        $overallBeds = LodgingSite::getOverallCapacity();
        $takenBeds = LodgingEntry::getOverallOccupancy();

        $availability = $overallBeds - $takenBeds;

        return $availability;
    }
}