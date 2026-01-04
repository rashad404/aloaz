<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "compotetion_images".
 *
 * @property integer $id
 * @property integer $compotetion_id
 * @property integer $user_id
 * @property integer $user_image_id
 * @property integer $like_count
 * @property integer $status
 * @property integer $image_time
 */
class CompotetionImages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'compotetion_images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['compotetion_id', 'user_id', 'user_image_id', 'like_count', 'status', 'image_time'], 'required'],
            [['compotetion_id', 'user_id', 'user_image_id', 'like_count', 'status', 'image_time'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'compotetion_id' => Yii::t('app', 'Compotetion ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'user_image_id' => Yii::t('app', 'User Image ID'),
            'like_count' => Yii::t('app', 'Like Count'),
            'status' => Yii::t('app', 'Status'),
            'image_time' => Yii::t('app', 'Image Time'),
        ];
    }
}
