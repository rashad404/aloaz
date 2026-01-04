<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vip_user".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $time
 */
class UserVip extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vip_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'time'], 'required'],
            [['user_id', 'time'], 'integer']
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
            'time' => Yii::t('app', 'Time'),
        ];
    }
}
