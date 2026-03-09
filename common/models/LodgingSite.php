<?php

namespace common\models;

use Yii;

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
            'notes' => 'Notas',
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

}
