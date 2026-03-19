<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "branch".
 *
 * @property int $id
 * @property string $description
 *
 * @property LodgingEntry[] $lodgingEntries
 */
class Branch extends \yii\db\ActiveRecord {


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'branch';
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
     * Gets query for [[LodgingEntries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLodgingEntries()
    {
        return $this->hasMany(LodgingEntry::class, ['branch_id' => 'id']);
    }


    /**
     * Lista de ramos para dropdown
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
