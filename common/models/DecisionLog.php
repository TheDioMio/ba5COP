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
            [['reason', 'impact'], 'default', 'value' => null],
            [['decided_at'], 'safe'],
            [['decided_by', 'entity_id'], 'required'],
            [['decided_by', 'entity_id'], 'integer'],
            [['status_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusType::class, 'targetAttribute' => ['status_type_id' => 'id']],
            [['reason', 'impact'], 'string', 'max' => 50],
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
            'reason' => 'Motivo',
            'impact' => 'Impacto',
            'decided_at' => 'Decidido às',
            'decided_by' => 'Decidido por',
            'entity_id' => 'ID da Entidade',
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
     * Gets query for [[StatusType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatusType()
    {
        return $this->hasOne(StatusType::class, ['id' => 'status_type_id']);
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

    /**
     * Este metodo atualiza as tabelas auditLog para ter um histórico automático de mudanças.
     * No caso, depois de uma ação CR(Create, Update), atualiza isso no auditLog.
     */
    public function afterSave($insert, $changedAttributes) {
        switch ($insert) {
            case (true):
                $action = 'CREATE';
                break;
            case (false):
                $action = 'UPDATE';
                break;
            default:
                $action = 'ERRO 101';
        }

        $entityID = $this->entity_id;
        $userID = Yii::$app->user->id;

        AuditLog::logAction($action, $entityID, $userID);
    }

    public function afterDelete() {
        $entityID = $this->entity_id;
        $userID = Yii::$app->user->id;
        $action = 'DELETE';

        AuditLog::logAction($action, $entityID, $userID);
    }
}
