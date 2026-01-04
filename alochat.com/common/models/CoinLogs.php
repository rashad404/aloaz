<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "coin_logs".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $user_id2
 * @property integer $coins
 * @property integer $type
 * @property string $text
 * @property string $date
 */
class CoinLogs extends \yii\db\ActiveRecord
{
    const LOG_BUY_COIN = 'buy_coin';
    const LOG_BUY_COIN_PORTMANAT = 'buy_coin_portmanat';
    const LOG_SEND_COIN = 'send_coin';
    const LOG_RECEIVE_COIN = 'receive_coin';
    const LOG_RECEIVE_COIN_ALOCHAT = 'receive_coin_alochat';
    const LOG_SET_VIP = 'set_vip';
    const LOG_ADD_POINT = 'add_point';
    const LOG_CHANGE_NICK = 'change_nick';
    const LOG_DELETE_NICK = 'delete_nick';
    public static $log_text = [
        'buy_coin' => 'Bal almaq',
        'buy_coin_portmanat' => 'Portmanat ilə bal almaq',
        'send_coin' => 'Bal göndərmək',
        'receive_coin' => 'Hədiyyə gələn bal',
        'set_vip' => 'Vip istifadəçi olmaq',
        'add_point' => 'Xal almaq',
        'change_nick' => 'Nik dəyişmək',
        'delete_nick' => 'Nik silmək',
        'receive_coin_alochat' => 'Alochatdan gələn bal'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'coin_logs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'coins', 'type', 'text', 'date'], 'required'],
            [['user_id', 'coins', 'type'], 'integer'],
            [['user_id2','date'], 'safe'],
            [['text'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'user_id2' => Yii::t('app', 'User ID'),
            'coins' => Yii::t('app', 'Coins'),
            'type' => Yii::t('app', 'Type'),
            'text' => Yii::t('app', 'Text'),
            'date' => Yii::t('app', 'Date'),
        ];
    }
}
