<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "location_type".
 *
 * @property int $id
 * @property string $description
 *
 * @property Location[] $locations
 */
class LocationType extends \yii\db\ActiveRecord {
    const TYPE_BUILDING = 1;
    const TYPE_AREA = 2;
    const TYPE_POINT = 3;
    const TYPE_ROAD = 4;
    const TYPE_VEDACAO = 5;
    const TYPE_PARKING = 6;
    const TYPE_NAVAIDS = 7;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'location_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['description'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Nome',
        ];
    }

    /**
     * Gets query for [[Locations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(Location::class, ['location_type_id' => 'id']);
    }

    /**
     * Lista de tipos de localizações para dropdown
     * [id => description]
     */
    public static function dropDown(): array
    {
        $rows = self::find()
            ->select(['id', 'description'])
            ->orderBy(['description' => SORT_ASC])
            ->asArray()
            ->all();

        return ArrayHelper::map($rows, 'id', 'description');
    }
}
