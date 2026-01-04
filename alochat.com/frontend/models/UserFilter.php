<?php
/**
  * User: yusif
 * Date: 7/13/2015
 * Time: 10:32 AM
 */

namespace frontend\models;

use Yii;
use common\models\City;
use common\models\Country;
use common\models\User;
use yii\base\Model;
use yii\web\Cookie;
use yii\web\Session;

class UserFilter extends Model {

   /** public variables for filter **/
    public  $countryId;
    public  $cityId;
    public  $ageRange;
    public  $sex;
    public  $onlineStatus;
    public  $issetPhoto;

    /**
     * @inheritdoc
     */

    public function rules()
    {
        return  [

            [['countryId','cityId'],'integer'],

            ['countryId','validateCountry'],

            ['cityId','validateCity'],

            ['sex','in','range' => [User::SEX_MAN,User::SEX_WOMAN,2]],

            ['onlineStatus','in','range' => [0,1]],

            ['issetPhoto','in','range' => [0,1]],

            ['ageRange','ValidateAgeRange'],


        ];

    }

    public function saveChanges()
    {

        $ageArr = explode(',',$this->ageRange);

        $ageMin = intval($ageArr[0]);

        $ageMAx = intval($ageArr[1]);

        $userFilter = [
            'country' => intval($this->countryId),
            'city' => intval($this->cityId),
            'sex' => intval($this->sex),
            'ageMin' => $ageMin,
            'ageMax' => $ageMAx,
            'online' => intval($this->onlineStatus),
            'photo' => intval($this->issetPhoto)
        ];


        \Yii::$app->response->cookies->add(new \yii\web\Cookie([
            'name' => 'userFilterData',
            'value' => $userFilter,
            'expire' => time() + 365 * 24 * 60 * 60,
        ]));

    }

    public function validateCountry($attribute)
    {
        $countryId = intval($this->$attribute);

        if($countryId > 0 && !Country::findOne($countryId)) {

            $this->addError($attribute,\Yii::t('app','Invalid country'));

        }
    }

    public function validateCity($attribute)
    {
        $cityId = intval($this->$attribute);

        if($cityId > 0 && !City::findOne($cityId)) {

            $this->addError($attribute,\Yii::t('app','Invalid City'));

        }
    }

    public function validateAgeRange($attribute)
    {
        $ageArr = explode(',',$this->$attribute);

        $ageArr[0] = intval($ageArr[0]);
        $ageArr[1] = intval($ageArr[1]);

        if( $ageArr[0] < User::AGE_MIN ||  $ageArr[1] > User::AGE_MAX) {

            $this->addError($attribute,Yii::t('app','Invalid age range'));

        }
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