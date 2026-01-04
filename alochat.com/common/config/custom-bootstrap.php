<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 16.04.2015
 * Time: 11:46
 */

Yii::$app->on(\yii\base\Application::EVENT_BEFORE_REQUEST, function ($event) {

    $languages = ['en', 'az','ru','tr'];
    if (isset(Yii::$app->request->cookies['alochat_language'])) {

        $lang = Yii::$app->request->cookies['alochat_language'];

        if (!empty($lang) and in_array($lang, $languages)) {

            Yii::$app->language = trim($lang);

        }
    }
    else{

        $ip =  Yii::$app->ipgeobase->getIP();

        $geoData = Yii::$app->ipgeobase->getLocation($ip);

        $country_code = strtolower(isset($geoData['country_code'])?$geoData['country_code']:false);

        if(!empty($country_code) && in_array(strtolower($country_code),$languages)){

            Yii::$app->language = trim(strtolower($geoData['country_code']));
        }

    }


});