<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "blocks".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $begin_time
 * @property integer $end_time
 * @property integer $blocked_time
 * @property integer $time
 * @property string $reason
 */
class Blocks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'blocks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'begin_time', 'time', 'reason'], 'required'],
            [['user_id', 'begin_time', 'end_time','blocked_time', 'time'], 'integer'],
            [['reason'], 'string', 'max' => 255]
        ];
    }


    public static function getTimesArray()
    {
      return   $times = [
            '0' => 'bitib',
            '60' => '1 dəq',
            '300' => '5 dəq',
            '900' => '15 dəq',
            '1800' => '30 dəq',
            '3600' => '1 saat',
            '43200' => '12 saat',
            '86400' => '24 saat',
            '604800' => '1 həftə',
            '2592000' => '1 ay',
            '31104000' => '12 ay',
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'İstifadəçi ID'),
            'begin_time' => Yii::t('app', 'Begin Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'time' => Yii::t('app', 'Time'),
            'reason' => Yii::t('app', 'Səbəb'),
        ];
    }
}
