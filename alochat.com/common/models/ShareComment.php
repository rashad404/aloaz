<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "share_comment".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $sid
 * @property string $comment
 * @property integer $time
 */
class ShareComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'share_comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'sid', 'comment', 'time'], 'required'],
            [['uid', 'sid', 'time'], 'integer'],
            [['comment'], 'string']
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
            'comment' => Yii::t('app', 'Comment'),
            'time' => Yii::t('app', 'Time'),
        ];
    }
}
