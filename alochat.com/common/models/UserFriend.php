<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_friend".
 *
 * @property integer $id
 * @property integer $user_1
 * @property integer $user_2
 * @property integer $ok
 * @property integer $request_time
 * @property integer $ok_time
 * @property integer $seen
 */
class UserFriend extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_friend';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_1', 'user_2'], 'required'],
            [['user_1', 'user_2', 'ok', 'request_time', 'ok_time', 'seen'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_1' => Yii::t('app', 'User 1'),
            'user_2' => Yii::t('app', 'User 2'),
            'ok' => Yii::t('app', 'Ok'),
            'request_time' => Yii::t('app', 'Request Time'),
            'ok_time' => Yii::t('app', 'Ok Time'),
            'seen' => Yii::t('app', 'Seen'),
        ];
    }
}
