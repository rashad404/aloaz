<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "image_comment".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $sid
 * @property string $comment
 * @property integer $time
 */
class ImageComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image_comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'image_id', 'comment', 'time'], 'required'],
            [['user_id', 'image_id', 'time'], 'integer'],
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
            'user_id' => Yii::t('app', 'User ID'),
            'image_id' => Yii::t('app', 'Image ID'),
            'comment' => Yii::t('app', 'Comment'),
            'time' => Yii::t('app', 'Time'),
        ];
    }
}
