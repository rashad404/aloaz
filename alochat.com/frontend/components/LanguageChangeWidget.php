<?php
/**
 * Created by elvin
 * Date: 08.12.2014
 * Time: 9:46
 */

namespace frontend\components;

use yii\base\Widget;
use app\models\MusicGenre;
use yii\db\Query;

class LanguageChangeWidget extends Widget
{
    public $languages;

    public function init()
    {
        parent::init();


        $this->languages = [

            'en' => ['short' => 'EN', 'full' => 'English'],
            'ru' => ['short' => 'RU', 'full' => 'Русский'],
            'az' => ['short' => 'AZ', 'full' => 'Azərbaycan'],
            'tr' => ['short' => 'TR', 'full' => 'Türkçe'],

        ];
    }

    public function run()
    {
        return $this->render('languageChange', [
            'languages' => $this->languages,
        ]);
    }
}