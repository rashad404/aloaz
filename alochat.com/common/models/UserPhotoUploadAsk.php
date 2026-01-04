<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_photo_upload_ask".
 *
 * @property integer $id
 * @property integer $user_from
 * @property integer $user_to
 * @property integer $add_time
 */
class UserPhotoUploadAsk extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_photo_upload_ask';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_from', 'user_to'], 'required'],
            [['user_from', 'user_to', 'add_time'], 'integer'],
            ['add_time','default','value'=>time()]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_from' => Yii::t('app', 'User From'),
            'user_to' => Yii::t('app', 'User To'),
            'add_time' => Yii::t('app', 'Add Time'),
        ];
    }
}
