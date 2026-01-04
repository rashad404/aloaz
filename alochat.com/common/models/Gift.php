<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "gift".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $name
 * @property string $icon
 * @property integer $coin
 * @property integer $status
 */
class Gift extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gift';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'status'], 'required'],
            [['category_id', 'coin', 'status'], 'integer'],
            [['name', 'icon'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'category_id' => Yii::t('app', 'Category ID'),
            'name' => Yii::t('app', 'Name'),
            'icon' => Yii::t('app', 'Icon'),
            'coin' => Yii::t('app', 'Coin'),
            'status' => Yii::t('app', 'Status'),
        ];
    }
}
