<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_block".
 *
 * @property integer $id
 * @property integer $block_from
 * @property integer $block_to
 * @property integer $time
 */
class UserBlock extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_block';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['block_from', 'block_to', 'time'], 'required'],
            [['block_from', 'block_to', 'time'], 'integer'],
            [['block_from', 'block_to'], 'unique', 'targetAttribute' => ['block_from', 'block_to'], 'message' => 'The combination of Block From and Like To has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'block_from' => Yii::t('app', 'Block From'),
            'block_to' => Yii::t('app', 'Block To'),
            'time' => Yii::t('app', 'Time'),
        ];
    }

    public static function checkUsersIsBlocked($user_one,$user_two) {
        $userBlock = UserBlock::find()->
        where(['block_from' => $user_one, 'block_to' => $user_two])
            ->orWhere(['block_to' => $user_one, 'block_from' => $user_two])
            ->one();
        return $userBlock ? true : false;
    }
}
