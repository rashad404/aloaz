<?php
namespace frontend\models;

use common\models\City;
use common\models\Country;
use common\models\User;
use yii\base\Model;
use Yii;

/**
 * ProfileSettings form
 */
class CitySelectForm extends Model
{
    public $countryId;
    public $cityId;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['countryId', 'cityId'], 'required'],

            [['countryId', 'cityId'], 'integer'],

            ['countryId', 'validateCountry'],

            ['cityId', 'validateCity'],
        ];
    }

    public function changeCity()
    {
        if ($this->validate()) {

            $user = User::findOne(Yii::$app->user->id);

            $user->country_id = $this->countryId;
            $user->city_id = $this->cityId;

            if ($user->save(false))
                return true;
            else
                return false;

        }

        return null;
    }

    public function validateCountry($attribute, $params)
    {
        if (!Country::findOne(intval($this->$attribute))) {
            $this->addError($attribute, 'Invalid country');
        }
    }

    public function validateCity($attribute, $params)
    {
        if (!City::findOne(intval($this->$attribute))) {
            $this->addError($attribute, 'Invalid city');
        }
    }

    public function attributeLabels()
    {

        return [

            'cityId' => Yii::t('app', 'City'),
            'countryId' => Yii::t('app', 'Country')

        ];
    }
}
