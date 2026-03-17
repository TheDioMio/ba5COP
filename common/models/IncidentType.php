<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "incident_type".
 *
 * @property int $id
 * @property string $description
 *
 * @property Incident[] $incidents
 */
class IncidentType extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'incident_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['description'], 'string', 'max' => 30],
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
     * Gets query for [[Incidents]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIncidents()
    {
        return $this->hasMany(Incident::class, ['incident_type_id' => 'id']);
    }

    /**
     * Lista de tipos de incidentes para dropdown
     * [id => description]
     */
    public static function dropDown(): array
    {
        $rows = self::find()
            ->select(['id', 'description'])
            ->asArray()
            ->all();

        return ArrayHelper::map($rows, 'id', 'description');
    }

}
