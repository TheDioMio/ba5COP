<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "decision_log".
 *
 * @property int $id
 * @property string|null $reason
 * @property string $decided_at
 * @property int $decided_by
 * @property int $entity_id
 *
 * @property User $decidedBy
 * @property Entity $entity
 */
class DecisionLog extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'decision_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reason'], 'default', 'value' => null],
            [['decided_at'], 'safe'],
            [['decided_by', 'entity_id'], 'required'],
            [['decided_by', 'entity_id'], 'integer'],
            [['reason'], 'string', 'max' => 30],
            [['entity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Entity::class, 'targetAttribute' => ['entity_id' => 'id']],
            [['decided_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['decided_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reason' => 'Reason',
            'decided_at' => 'Decided At',
            'decided_by' => 'Decided By',
            'entity_id' => 'Entity ID',
        ];
    }

    /**
     * Gets query for [[DecidedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDecidedBy()
    {
        return $this->hasOne(User::class, ['id' => 'decided_by']);
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



    /**
     * Devolve o array total das últimas 10 decisões
     */
    public static function getLatest10Decisions() {
        return DecisionLog::find()
            ->orderBy([
                'decided_at' => SORT_DESC
            ])
            ->limit(10)
            ->all();
    }
}
