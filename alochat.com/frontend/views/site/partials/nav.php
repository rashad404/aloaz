<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 18.04.2015
 * Time: 16:28
 */
use yii\helpers\Url;
?>

<nav class="israfil">
    <div class="container">
        <ul class="pull-left">
            <?php
                $az='';$ru='';$en='';$tr='';
                $lang= Yii::$app->language;
                $$lang = 'lang-active';
            ?>
            <li>
                <a onclick="goToLink(this);" href="<?= Url::to(['site/language', 'id' => 'az']) ?>" class="<?= $az?>">
                    <span class="lang-full">Azərbaycan</span>
                    <span class="lang-short">Aze</span>
                </a>
            </li>
            <li>
                <a onclick="goToLink(this);" href="<?= Url::to(['site/language', 'id' => 'ru']) ?>" class="<?= $ru?>">
                    <span class="lang-full">Русский</span>
                    <span class="lang-short">Рус</span>
                </a>
            </li>
            <li>
                <a  onclick="goToLink(this);" href="<?= Url::to(['site/language', 'id' => 'en']) ?>" class="<?= $en?>">
                    <span class="lang-full">English</span>
                    <span class="lang-short">Eng</span>
                </a>
            </li>
            <li>
                <a onclick="goToLink(this);" href="<?= Url::to(['site/language', 'id' => 'tr']) ?>" class="<?= $tr?>">
                    <span class="lang-full">Türkçe</span>
                    <span class="lang-short">Tur</span>
                </a>
            </li>
        </ul>


        <div class="login-pointer">
            <a id="loginBtn" class="btn btn-default btn-xs  login-btn" href="#section5"
               role="button"><?= Yii::t('app', 'Login') ?></a>

            <p class="pull-left"><?= Yii::t('app', 'Already have an account?') ?></p>
        </div>

    </div>
</nav>  <!-- Language change nav  -->
