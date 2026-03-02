<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "entity_type".
 *
 * @property int $id
 * @property string $name
 *
 * @property StatusType[] $statusTypes
 */
class EntityType extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entity_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 30],
            [['name'], 'unique'],
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
        ];
    }

    /**
     * Gets query for [[StatusTypes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatusTypes()
    {
        return $this->hasMany(StatusType::class, ['entity_type_id' => 'id']);
    }

}
