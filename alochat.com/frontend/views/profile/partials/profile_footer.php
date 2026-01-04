<?php
use \yii\helpers\Url;
?>
<footer class="footer footer-chat">

    <div class="line1">
        <div class="container">
        <div class="row">
            <div class="col-xs-12 hidden-sm hidden-md hidden-lg footer-block text-center" style="margin-top: -11px;margin-bottom: -6px;;padding-bottom:15px; padding-top: 0px;padding-right: 0px !important;padding-left: 0px !important; ">
                <span class="footer-lang"><a href="<?= Url::to(["/site/search"])?>"><?php echo Yii::t('app','Search')?></a></span>
                <span class="footer-lang"><a href="<?= Url::to(["/site/users"])?>"><?= Yii::t('app','Users')?></a></span>
                <span class="footer-lang"><a href="<?= Url::to(["/messages/index"])?>"><?= Yii::t('app','Messages')?></a></span>
                <span class="footer-lang"><a href="<?= Url::to(["/site/shares"])?>"><?= Yii::t('app','Shares')?></a></span>
            </div>
            <div class="col-md-3 col-md-offset-9 footer-social hidden-xs">

                <a href="#top">
                    <div class="footer-top z-depth-1">
                       <img src="/images/icons/top.png">
                    </div>
                </a>
                <a href="#">
                    <div  class="footer-facebook z-depth-1">
                        <img src="/images/icons/t.png">
                    </div>
                </a>
                <a href="#">
                    <div class="footer-twitter z-depth-1">
                        <img src="/images/icons/f.png">
                    </div>
                </a>
                <div class="clearfix"></div>
            </div>
                 <div class="col-md-4 footer-block footer-block1 hidden-xs">

                    <?php
                    $az='';$ru='';$en='';$tr='';
                    $lang= Yii::$app->language;
                    $$lang = 'lang-active';
                    ?>
                    <a onclick="goToLink(this);" href="<?= Url::to(['site/language', 'id' => 'az']) ?>">
                        <span class="footer-lang <?= $az?>">Azərbaycan</span>
                    </a>
                    <a onclick="goToLink(this);" href="<?= Url::to(['site/language', 'id' => 'ru']) ?>">
                        <span class="footer-lang <?= $ru?>">Русский</span>
                    </a>
                    <a  onclick="goToLink(this);" href="<?= Url::to(['site/language', 'id' => 'en']) ?>">
                        <span class="footer-lang <?= $en?>">English</span>
                    </a>
                    <a onclick="goToLink(this);" href="<?= Url::to(['site/language', 'id' => 'tr']) ?>">
                        <span class="footer-lang <?= $tr;?>">Türkçe</span>
                    </a>
                </div>
                <div class="col-md-5 col-xs-12 col-sm-12 footer-block hidden-xs">
                    <span class="footer-lang"><a href="" data-toggle="modal" data-target="#aboutus-modal"><?php echo Yii::t('app','About')?></a></span>
                    <span class="footer-lang"><a href="#" data-toggle="modal" data-target="#privacy-modal"><?= Yii::t('app','Privacy & Policy terms')?></a></span>
                    <span class="footer-lang"><a href="#" data-toggle="modal" data-target="#contact-modal"><?= Yii::t('app','Contact')?></a></span>
                 </div>

                <div class="col-md-3 hidden-xs footer-block" style="text-align: right">
                    <span style="margin-right: 20px;text-align: right"><a href="" data-toggle="modal" data-target="#aboutus-modal">İstifadəçi sayı: <?php echo \common\models\User::getAllUserCount()?> nəfər</a></span>

                </div>
         </div>


        </div>
    </div>

    <div class="line2">
        <div class="container">
            <p class="pull-center"><?= Yii::t('app', 'Copyright') ?>&nbsp;&copy;
                Alochat.com. <?= date('Y') ?> <?= Yii::t('app', 'All rights reserved') ?></p>
        </div>
    </div>
</footer>
