<?php

namespace common\models;

use DateTime;
use Yii;

/**
 * This is the model class for table "request".
 *
 * @property int $id
 * @property int $is_external
 * @property string $origin
 * @property string $details
 * @property int $priority_id
 * @property int $status_type_id
 * @property string $created_at
 * @property int $entity_id
 * @property int|null $request_type_id
 * @property int|null $quantity
 *
 * @property Entity $entity
 * @property Priority $priority
 * @property StatusType $statusType
 * @property RequestType $requestType
 */
class Request extends \yii\db\ActiveRecord
{
    const EXTERNAL_REQUEST = 1;
    const NOT_EXTERNAL_REQUEST = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quantity'], 'default', 'value' => 1],
            [['is_external', 'origin', 'details', 'priority_id', 'status_type_id', 'entity_id'], 'required'],
            [['is_external', 'priority_id', 'status_type_id', 'entity_id', 'request_type_id', 'quantity'], 'integer'],
            [['created_at'], 'safe'],
            [['origin'], 'string', 'max' => 30],
            [['details'], 'string', 'max' => 120],
            [['request_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => RequestType::class, 'targetAttribute' => ['request_type_id' => 'id']],
            [['entity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Entity::class, 'targetAttribute' => ['entity_id' => 'id']],
            [['priority_id'], 'exist', 'skipOnError' => true, 'targetClass' => Priority::class, 'targetAttribute' => ['priority_id' => 'id']],
            [['status_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => StatusType::class, 'targetAttribute' => ['status_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'is_external' => 'Externo?',
            'origin' => 'Origem',
            'details' => 'Detalhes',
            'priority_id' => 'ID da Prioridade',
            'status_type_id' => 'Status',
            'created_at' => 'Criado às',
            'entity_id' => 'ID da Entidade',
            'request_type_id' => 'ID do Tipo de Pedido',
            'quantity' => 'Quantidade',
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

    /**
     * Gets query for [[Priority]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPriority()
    {
        return $this->hasOne(Priority::class, ['id' => 'priority_id']);
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
     * Gets query for [[RequestType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequestType()
    {
        return $this->hasOne(RequestType::class, ['id' => 'request_type_id']);
    }

    /**
     * Devolve o número total de pedidos EXTERNOS (todos os estados)
     */
    public static function getExternalRequests()
    {
        return self::find()
            ->where(['is_external' => self::EXTERNAL_REQUEST])
            ->asArray()
            ->all();
    }

    /**
     * Devolve o número total de pedidos EXTERNOS RESOLVIDOS
     */
    public static function getExternalDone()
    {
        return self::find()
            ->where(['is_external' => self::EXTERNAL_REQUEST])
            ->andWhere(['status_type_id' => StatusType::STATUS_REQUEST_DONE])
            ->asArray()
            ->all();
    }

    /**
     * Devolve o número total de pedidos EXTERNOS ATIVOS
     */
    public static function getActiveExternal()
    {
        return self::find()
            ->where(['is_external' => self::EXTERNAL_REQUEST])
            ->andWhere([
                'status_type_id' => [
                    StatusType::STATUS_REQUEST_NEW,
                    StatusType::STATUS_REQUEST_IN_PROGRESS,
                ]
            ])
            ->with(['priority', 'statusType'])
            ->asArray()
            ->all();
    }

    /**
     * Devolve o número total de pedidos EXTERNOS REJEITADOS
     */
    public static function getExternalRejected()
    {
        return self::find()
            ->where(['is_external' => self::EXTERNAL_REQUEST])
            ->andWhere(['status_type_id' => StatusType::STATUS_REQUEST_REJECTED])
            ->asArray()
            ->all();
    }

    /**
     * Devolve o número total de pedidos EM ANÁLISE
     */
    public static function getExternalInAnalisis()
    {
        return self::find()
            ->where(['is_external' => self::EXTERNAL_REQUEST])
            ->andWhere([
                'status_type_id' => [
                    StatusType::STATUS_REQUEST_IN_PROGRESS,
                ]
            ])
            ->with(['priority', 'statusType'])
            ->asArray()
            ->all();
    }

    /**
     * Devolve o número total de pedidos ACEITES
     */
    public static function getExternalAccepted()
    {
        return self::find()
            ->where(['is_external' => self::EXTERNAL_REQUEST])
            ->andWhere([
                'status_type_id' => [
                    StatusType::STATUS_REQUEST_APPROVED,
                    StatusType::STATUS_REQUEST_DONE,
                ]
            ])
            ->with(['priority', 'statusType'])
            ->asArray()
            ->all();
    }

    /**
     * Devolve a query que é usada por dataproviders, neste caso todos os pedidos externos que estão ativos.
     */
    public static function findActiveExternal()
    {
        return self::find()
            ->where(['is_external' => self::EXTERNAL_REQUEST])
            ->andWhere([
                'status_type_id' => [
                    StatusType::STATUS_REQUEST_NEW,
                    StatusType::STATUS_REQUEST_IN_PROGRESS,
                ]
            ])
            ->with(['priority', 'statusType']);
    }

    /**
     * Devolve o número total de pedidos NOVOS (submetidos nas últimas 24H)
     * Pedidos que foram submetidos nas últimas 24H.
     */
    public static function getExternalNew()
    {
        $datetime = date('Y-m-d H:i:s', strtotime('-24 hours'));

        return self::find()
            ->where(['>=', 'created_at', $datetime])
            ->andWhere(['is_external' => self::EXTERNAL_REQUEST])
            ->asArray()
            ->all();
    }

    /**
     * Devolve os pedidos de "X" tipo de request_type, que estejam já fornecidos
     */
    public static function getNumberRequestsOfType($requestType, $external)
    {
        $is_external = $external ? self::EXTERNAL_REQUEST : self::NOT_EXTERNAL_REQUEST;

        $exists = self::find()
            ->where(['request_type_id' => $requestType])
            ->exists();

        if (!$exists) {
            return -1;
        }

        return self::find()
            ->where(['request_type_id' => $requestType])
            ->andWhere(['is_external' => $is_external])
            ->andWhere(['status_type_id' => StatusType::STATUS_REQUEST_DONE])
            ->asArray()
            ->all();
    }

    /**
     * Quantidade de ajudas feitas por "X" tipo de pedido
     * DEVOLVE: int, soma da quantidade de cada registo com esse tipo de pedido
     *
     * ERROS: -2 em caso de erro no parâmetro $external
     */
    public static function getAllNumberRequestsOfType($requestType, $external = null)
    {
        if ($external !== null) {
            switch (strtolower($external)) {
                case 'external':
                    $query = self::EXTERNAL_REQUEST;
                    break;
                case 'internal':
                    $query = self::NOT_EXTERNAL_REQUEST;
                    break;
                default:
                    return -2;
            }
        }

        if ($external === null) {
            return self::find()
                ->where(['request_type_id' => $requestType])
                ->andWhere(['status_type_id' => StatusType::STATUS_REQUEST_DONE])
                ->sum('quantity');
        }

        return self::find()
            ->where(['request_type_id' => $requestType])
            ->andWhere(['is_external' => $query])
            ->andWhere(['status_type_id' => StatusType::STATUS_REQUEST_DONE])
            ->sum('quantity');
    }

    /**
     * Quantidade de ajudas feitas por "X" tipo de pedido, durante X tempo.
     * DEVOLVE: int, soma da quantidade de cada registo com esse tipo de pedido
     *
     * Parâmetro opcional EXTERNAL, que é boolean e deixa filtrar entre pedidos externos e internos
     *
     * Caso dê erro, devolve -1
     */
    public static function getRequestsOfTypeWithin($requestType, $time, $external = null)
    {
        switch (strtolower($time)) {
            case 'today':
                $comp1 = new DateTime('today');
                $comp2 = new DateTime('tomorrow');
                break;

            case 'yesterday':
                $comp1 = new DateTime('yesterday');
                $comp2 = new DateTime('today');
                break;

            default:
                return -1;
        }

        $query = self::find()
            ->where(['request_type_id' => $requestType])
            ->andWhere(['status_type_id' => StatusType::STATUS_REQUEST_DONE])
            ->andWhere(['>=', 'created_at', $comp1->format('Y-m-d H:i:s')])
            ->andWhere(['<', 'created_at', $comp2->format('Y-m-d H:i:s')]);

        if ($external !== null) {
            switch (strtolower($external)) {
                case 'external':
                    $query->andWhere(['is_external' => self::EXTERNAL_REQUEST]);
                    break;

                case 'internal':
                    $query->andWhere(['is_external' => self::NOT_EXTERNAL_REQUEST]);
                    break;

                default:
                    return -2;
            }
        }

        return (int)($query->sum('quantity') ?? 0);
    }
}