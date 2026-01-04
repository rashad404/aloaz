<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "city".
 *
 * @property integer $id
 * @property string $name
 * @property string $country_code
 * @property integer $is_capital
 * @property integer $country_id
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_code'], 'required'],
            [['is_capital'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['country_code'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'country_code' => Yii::t('app', 'Country Code'),
            'is_capital' => Yii::t('app', 'Is Capital'),
        ];
    }

    public function getCountry(){

        return $this->hasOne(Country::className(),['id'=>'country_id']);
    }

    public static function getCityName($city_id)
    {
        $cityName = '';
        $city = City::find()->where(['id'=> $city_id])->one();
        if($city!=NULL)
        $cityName = $city->name;
        return $cityName;
    }
}
