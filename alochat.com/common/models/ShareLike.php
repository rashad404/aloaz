<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "share_like".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $sid
 * @property integer $time
 */
class ShareLike extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'share_like';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'sid', 'time'], 'required'],
            [['uid', 'sid', 'time'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'uid' => Yii::t('app', 'Uid'),
            'sid' => Yii::t('app', 'Sid'),
            'time' => Yii::t('app', 'Time'),
        ];
    }
}
