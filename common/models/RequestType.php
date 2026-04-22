<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "request_type".
 *
 * @property int $id
 * @property string $description
 *
 * @property Request[] $requests
 */
class RequestType extends \yii\db\ActiveRecord
{
    const TYPE_MEAL = 1;
    const TYPE_BATH = 2;
    const TYPE_BED = 3;
    const TYPE_MACHINE_HOURS = 4;
    const TYPE_TEAM_HOURS = 5;
    const TYPE_LOGISTIC_SUPPORT = 6;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'request_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['description'], 'string', 'max' => 50],
            [['description'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Descrição',
        ];
    }

    /**
     * Gets query for [[Requests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Request::class, ['request_type_id' => 'id']);
    }

    /**
     * Lista de tipos de pedidos para dropdown
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
