<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_gift".
 *
 * @property integer $id
 * @property integer $gift_from
 * @property integer $gift_to
 * @property integer $gift_id
 * @property string $comment
 * @property integer $time
 * @property integer $seen
 */
class UserGift extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_gift';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gift_from', 'gift_to', 'gift_id', 'time'], 'required'],
            [['gift_from', 'gift_to', 'gift_id', 'time','seen'], 'integer'],
            [['comment'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'gift_from' => Yii::t('app', 'Gift From'),
            'gift_to' => Yii::t('app', 'Gift To'),
            'gift_id' => Yii::t('app', 'Gift ID'),
            'comment' => Yii::t('app', 'Comment'),
            'time' => Yii::t('app', 'Time'),
            'seen' => Yii::t('app', 'Seen'),
        ];
    }
}
