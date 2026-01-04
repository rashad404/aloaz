<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "image_like".
 *
 * @property integer $id
 * @property integer $uid
 * @property integer $sid
 * @property integer $time
 */
class ImageLike extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image_like';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'image_id', 'time'], 'required'],
            [['user_id', 'image_id', 'time'], 'integer']
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
            'time' => Yii::t('app', 'Time'),
        ];
    }
}
