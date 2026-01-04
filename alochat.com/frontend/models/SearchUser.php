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
use yii\db\Query;
use yii\web\Cookie;
use yii\web\Session;

class SearchUser extends Model {

    /** public variables for filter **/
    public  $countryId;
    public  $cityId;
    public  $ageRange;
    public  $sex;
    public  $onlineStatus;
    public  $issetPhoto;
    public  $login;
    public  $similarity;

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

            ['similarity','in','range' => [0,1]],
            ['login', 'string']


        ];

    }


    public function findUsersForSearch()
    {

        $ageArr = explode(',',$this->ageRange);

        $ageMin = intval($ageArr[0]);

        $ageMAx = intval($ageArr[1]);

        $this->ageRange = '[' . $ageMin . ',' . $ageMAx . ']';

        $params = [
            'country' => intval($this->countryId),
            'city' => intval($this->cityId),
            'sex' => intval($this->sex),
            'ageMin' => $ageMin,
            'ageMax' => $ageMAx,
            'online' => intval($this->onlineStatus),
            'photo' => intval($this->issetPhoto),
            'login' => $this->login,
            'similarity' => $this->similarity
        ];


        $users = [];

        $qr = (new Query());

        $qr->select(['U.full_name','U.nickname',
            'U.age',
            'U.id',
            'U.sex',
            'U.profile_photo',
            'U.last_activity',
            'U.last_post',
            'C.name as city_name'])
            ->from('user U')
            ->leftJoin('city C', 'U.city_id=C.id')
            ->where(['U.status' => User::STATUS_ACTIVE, 'U.role' => User::ROLE_USER])
            ->andWhere(['!=','U.id',Yii::$app->user->id])
            //->andWhere(['!=','U.f_row',1])
            ->andWhere(['between','U.age',$params['ageMin'], $params['ageMax']]);

        if($params['photo']>0){
            $qr->andWhere("U.profile_photo!='' AND U.profile_photo IS NOT NULL");
        }

        if($params['country'] > 0) {
            $qr->andWhere(['U.country_id' => $params['country']]);
        }

        if($params['city'] > 0) {
            $qr->andWhere(['U.city_id' => $params['city']]);
        }

        if($params['online'] > 0) {

            $qr->andWhere([ '>', 'last_activity', (time() - Yii::$app->params['userOnlineStatusCheckTime'])]);
        }

        if($params['sex'] < 2) {
            $qr->andWhere(['U.sex' => $params['sex']]);
        }

        if($params["login"]!=""){
            if($params["similarity"] == 0){
                $qr->andWhere(['U.nickname' => $params['login']]);
            }else {
                $qr->andWhere('U.nickname LIKE "%'.$params["login"].'%"');

            }
        }

        return $qr;

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