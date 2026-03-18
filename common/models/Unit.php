<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "unit".
 *
 * @property int $id
 * @property string $name
 * @property int $branch_id
 * @property string|null $created_at
 *
 * @property Branch $branch
 * @property LodgingEntry[] $lodgingEntries
 */
class Unit extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'unit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'branch_id'], 'required'],
            [['branch_id'], 'integer'],
            [['created_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::class, 'targetAttribute' => ['branch_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'branch_id' => 'Branch ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Branch]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBranch()
    {
        return $this->hasOne(Branch::class, ['id' => 'branch_id']);
    }

    /**
     * Gets query for [[LodgingEntries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLodgingEntries()
    {
        return $this->hasMany(LodgingEntry::class, ['unit_id' => 'id']);
    }


    /**
     * Lista de unidades para dropdown
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

}
