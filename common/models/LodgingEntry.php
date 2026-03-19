<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lodging_entry".
 *
 * @property int $id
 * @property int $lodging_site_id
 * @property int $people_count
 * @property string $checkin_at
 * @property string $checkout_at
 * @property string|null $notes
 *
 * @property LodgingSite $lodgingSite
 */
class LodgingEntry extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lodging_entry';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['notes'], 'default', 'value' => null],
            [['lodging_site_id', 'people_count', 'checkin_at'], 'required'],
            [['lodging_site_id', 'people_count'], 'integer'],
            [['checkin_at', 'checkout_at'], 'safe'],
            [['checkout_at'], 'default', 'value' => null],
            [['notes'], 'string', 'max' => 30],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Unit::class, 'targetAttribute' => ['unit_id' => 'id']],
            [['lodging_site_id'], 'exist', 'skipOnError' => true, 'targetClass' => LodgingSite::class, 'targetAttribute' => ['lodging_site_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lodging_site_id' => 'ID do Alojamento',
            'unit_id' => 'ID da Unidade',
            'people_count' => 'N.º de Pessoas',
            'checkin_at' => 'Data Checkin',
            'checkout_at' => 'Data Checkout',
            'notes' => 'OBS.',
        ];
    }

    /**
     * Gets query for [[Unit]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Unit::class, ['id' => 'unit_id']);
    }

    /**
     * Gets query for [[LodgingSite]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLodgingSite()
    {
        return $this->hasOne(LodgingSite::class, ['id' => 'lodging_site_id']);
    }

    /**
     * Devolve o número total de camas OCUPADAS em todos os alojamentos.
     *
     */
    public static function getOverallOccupancy(){
        $takenBeds = self::find()
            ->where(['checkout_at' => null])
            ->sum('people_count');
        return $takenBeds;
    }

    /**
     * Devolve o número total de camas OCUPADAS por efetivo EXTERNO
     */
    public static function getExternalOccupancy() {
        $externalOccupancy = self::find()
            ->joinWith('unit')
            ->where(['lodging_entry.checkout_at' => null])
            ->andWhere(['!=', 'unit.branch_id', 1])
            ->sum('lodging_entry.people_count');

        return $externalOccupancy;
    }

}
