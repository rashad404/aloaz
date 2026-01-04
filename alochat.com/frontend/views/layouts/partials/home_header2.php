<?php
use yii\helpers\Url;
?>
<div id="header">
    <div class="row">
        <div class="col-md-2 col-xs-6">
            <a href="/"><img src="<?= Url::base()?>/images/alochat_logo.png" class="logo"></a>
        </div>
        <div class="col-md-10 col-xs-6 header-right">

            <div class="social">
                <div class="languages">
                    <ul class="nav nav-pills  hidden-xs hidden-sm">
                        <?php
                        $az='';$ru='';$en='';$tr='';
                        $lang= Yii::$app->language;
                        $$lang = 'active';
                        ?>
                        <li  class="<?= $az?>">
                            <a onclick="goToLink(this);" href="<?= Url::to(['site/language2', 'id' => 'az']) ?>">
                                Azərbaycan
                            </a>
                        </li>
                        <li  class="<?= $ru?>">
                            <a onclick="goToLink(this);" href="<?= Url::to(['site/language2', 'id' => 'ru']) ?>">
                                Русский
                            </a>
                        </li>
                        <li class="<?= $en?>">
                            <a  onclick="goToLink(this);" href="<?= Url::to(['site/language2', 'id' => 'en']) ?>">
                                English
                            </a>
                        </li>
                        <li class="<?= $tr?>">
                            <a onclick="goToLink(this);" href="<?= Url::to(['site/language2', 'id' => 'tr']) ?>">
                                Türkçe
                            </a>
                        </li>
                        <!--<li role="presentation" ><a href="#">Azərbaycan</a></li>
                        <li role="presentation"><a href="#">Русский</a></li>
                        <li role="presentation" class="active"><a href="#">English</a></li>
                        <li role="presentation"><a href="#">Türkçe  </a></li>-->
                    </ul>
                    <div class="hidden-md hidden-lg">
                        <select class="form-control">
                            <option>Azərbaycan</option>
                            <option>Русский</option>
                            <option selected="selected">English</option>
                            <option>Türkçe</option>
                        </select>
                    </div>

                </div>
                <div class="hidden-xs hidden-sm pull-right">
                    <div class="social-twitter">
                        <img src="<?= Url::base()?>/images/icons/twitter.png">
                    </div>
                    <div class="social-facebook">
                        <img src="<?= Url::base()?>/images/icons/facebook.png">
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>

        </div>
    </div>
</div>
<div class="clearfix"></div>