<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_like".
 *
 * @property integer $id
 * @property integer $like_from
 * @property integer $like_to
 * @property integer $time
 * @property integer $seen
 */
class UserLike extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_like';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['like_from', 'like_to', 'time'], 'required'],
            [['like_from', 'like_to', 'time', 'seen'], 'integer'],
            [['like_from', 'like_to'], 'unique', 'targetAttribute' => ['like_from', 'like_to'], 'message' => 'The combination of Like From and Like To has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'like_from' => Yii::t('app', 'Like From'),
            'like_to' => Yii::t('app', 'Like To'),
            'time' => Yii::t('app', 'Time'),
            'seen' => Yii::t('app', 'Seen'),
        ];
    }
}
