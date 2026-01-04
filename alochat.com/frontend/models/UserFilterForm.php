<?php
namespace frontend\models;

use common\models\City;
use common\models\Country;
use common\models\User;
use yii\base\Model;
use Yii;
use yii\web\Session;

/**
 * DiscoveryFilter form
 */
class UserFilterForm extends Model
{
    public $countryId;
    public $cityId;
    public $ageRange;
    public $sex;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['countryId', 'cityId'], 'integer'],

            ['countryId', 'validateCountry'],

            ['sex', 'in', 'range' => [USER::SEX_MAN, USER::SEX_WOMAN, 2]],

            ['cityId', 'validateCity'],

            ['ageRange', 'validateAgeRange'],
        ];
    }

    public function saveChanges()
    {

        $ageArr = explode(',', $this->ageRange);

        $ageMin = intval($ageArr[0]);

        $ageMax = intval($ageArr[1]);



        $discoveryFilter = [
            'country' => intval($this->countryId),
            'city' => intval($this->cityId),
            'sex' => intval($this->sex),
            'ageMin' => $ageMin,
            'ageMax' => $ageMax
        ];

        Yii::$app->response->cookies->add(new \yii\web\Cookie([
            'name' => 'filterData',
            'value' => $discoveryFilter,
            'expire' => time() + 365 * 24 * 60 * 60,

        ]));



    }


    public function validateCountry($attribute)
    {
        $countryId = intval($this->$attribute);

        if ($countryId > 0 && !Country::findOne($countryId)) {

            $this->addError($attribute, 'Invalid country');
        }
    }

    public function validateCity($attribute)
    {
        $cityId = intval($this->$attribute);

        if ($cityId > 0 && !City::findOne($cityId)) {

            $this->addError($attribute, 'Invalid city');
        }
    }

    public function validateAgeRange($attribute)
    {
        $ageArr = explode(',', $this->$attribute);

        $ageArr[0] = intval($ageArr[0]);
        $ageArr[1] = intval($ageArr[1]);

        if ($ageArr[0] < User::AGE_MIN || $ageArr[1] > User::AGE_MAX)
            $this->addError($attribute, 'Invalid age range');

    }

    public function attributeLabels()
    {

        return [

            'cityId' => Yii::t('app', 'City'),
            'countryId' => Yii::t('app', 'Country'),
            'ageRange' => Yii::t('app', 'Age'),
        ];
    }
}
