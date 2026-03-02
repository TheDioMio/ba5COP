<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "entity_update".
 *
 * @property int $id
 * @property int $entity_id
 * @property int $created_by
 * @property string $created_at
 * @property string $note
 *
 * @property Entity $entity
 */
class EntityUpdate extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'entity_update';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['entity_id', 'created_by', 'note'], 'required'],
            [['entity_id', 'created_by'], 'integer'],
            [['created_at'], 'safe'],
            [['note'], 'string', 'max' => 120],
            [['entity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Entity::class, 'targetAttribute' => ['entity_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entity_id' => 'Entity ID',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'note' => 'Note',
        ];
    }

    /**
     * Gets query for [[Entity]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEntity()
    {
        return $this->hasOne(Entity::class, ['id' => 'entity_id']);
    }

}
