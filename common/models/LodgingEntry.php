<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "lodging_entry".
 *
 * @property int $id
 * @property int $lodging_site_id
 * @property int $branch_id
 * @property int $people_count
 * @property string $checkin_at
 * @property string $checkout_at
 * @property string|null $notes
 *
 * @property Branch $branch
 * @property LodgingSite $lodgingSite
 */
class LodgingEntry extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lodging_entry';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['notes'], 'default', 'value' => null],
            [['lodging_site_id', 'branch_id', 'people_count', 'checkin_at', 'checkout_at'], 'required'],
            [['lodging_site_id', 'branch_id', 'people_count'], 'integer'],
            [['checkin_at', 'checkout_at'], 'safe'],
            [['notes'], 'string', 'max' => 30],
            [['branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::class, 'targetAttribute' => ['branch_id' => 'id']],
            [['lodging_site_id'], 'exist', 'skipOnError' => true, 'targetClass' => LodgingSite::class, 'targetAttribute' => ['lodging_site_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lodging_site_id' => 'Lodging Site ID',
            'branch_id' => 'Branch ID',
            'people_count' => 'People Count',
            'checkin_at' => 'Checkin At',
            'checkout_at' => 'Checkout At',
            'notes' => 'Notes',
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
     * Gets query for [[LodgingSite]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLodgingSite()
    {
        return $this->hasOne(LodgingSite::class, ['id' => 'lodging_site_id']);
    }

}
