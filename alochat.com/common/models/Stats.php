<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "stats".
 *
 * @property integer $id
 * @property integer $all_day
 * @property integer $all_24
 * @property integer $back_24
 * @property integer $back_3
 * @property integer $back_7
 * @property integer $back_10
 * @property integer $back_30
 * @property string $date
 */
class Stats extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stats';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['all_day', 'all_24', 'back_24', 'back_3', 'back_7', 'back_10', 'back_30', 'date'], 'required'],
            [['all_day', 'all_24', 'back_24', 'back_3', 'back_7', 'back_10', 'back_30'], 'integer'],
            [['date'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'all_day' => Yii::t('app', 'All Day'),
            'all_24' => Yii::t('app', 'All 24'),
            'back_24' => Yii::t('app', 'Back 24'),
            'back_3' => Yii::t('app', 'Back 3'),
            'back_7' => Yii::t('app', 'Back 7'),
            'back_10' => Yii::t('app', 'Back 10'),
            'back_30' => Yii::t('app', 'Back 30'),
            'date' => Yii::t('app', 'Date'),
        ];
    }
}
