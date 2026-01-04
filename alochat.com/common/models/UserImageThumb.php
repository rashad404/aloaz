<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_image_thumb".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $path
 * @property integer $add_date
 */
class UserImageThumb extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_image_thumb';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'path', 'id', 'add_date'], 'required'],
            [['user_id',  'add_date'], 'integer'],
            [['path'], 'string', 'max' => 255]
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
            'path' => Yii::t('app', 'Path'),
            'add_date' => Yii::t('app', 'Add Date'),
        ];
    }
}
