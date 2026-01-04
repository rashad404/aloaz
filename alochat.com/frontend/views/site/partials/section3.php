<?php
/**
 * Created by PhpStorm.
 * User: Elvin
 * Date: 18.04.2015
 * Time: 16:28
 */
use yii\helpers\Url;
use common\models\User;

?>
<div class="main">

    <h2><?= Yii::t('app', 'Our statistics') ?></h2>

    <p><?= Yii::t('app', 'Millons of people from across the globe are meeting each other and <br/> building out their relationship on {site_name} right now!', ['site_name' => 'Alo Chat']) ?></p>

    <div class="statistics-block">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6" style="text-align: center">
                    <div class="st-left" style="display: inline-block">
                        <div class="sprite-ic people"></div>
                        <p>
                            <span class="number"><?= User::getAllUserCount() ?></span>
                            <span class="desc clearfix"> <?= Yii::t('app', 'total network user') ?></span>
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6" style="text-align: center">
                    <div class="st-right" style="display: inline-block;">
                        <div class="sprite-ic calendar"></div>
                        <p>
                            <span class="number"><?= User::getOnlineUserCount() ?></span>
                            <span class="desc clearfix"> <?= Yii::t('app', 'total user online') ?></span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 20px; ">
                <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6" style="text-align: center">
                    <div class="feature-left features" style="display: inline-block">
                        <div class="sprite-ic message "></div>
                        <p>
                            <span class="feature-name">    <?=Yii::t('app', 'Messages')?></span>
                               <span class="feature-description">
                              <?= Yii::t('app', 'Eveyone gets at least<br/>one message per session'); ?>
                             </span>
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6" style="text-align: center">
                    <div class="feature-right features" style="display: inline-block">

                        <div class="sprite-ic dialogue"></div>
                        <p>
                            <span class="feature-name"> <?=Yii::t('app', 'Dialogues')?></span>
                              <span class="feature-description">
                             <?= Yii::t('app', 'Every second contacts  <br/> eads to a new dialogue')?>
                          </span>

                        </p>

                    </div>
                </div>
            </div>
        </div>

        <!--<div class="st-left">
            <div class="sprite-ic people"></div>
            <p>
                <span class="number"><?/*= User::getAllUserCount() + 100000 */?></span>
                <span class="desc clearfix"> <?/*= Yii::t('app', 'total network user') */?></span>
            </p>

        </div>
        <div class="st-right">
            <div class="sprite-ic calendar"></div>
            <p>
                <span class="number"><?/*= User::getOnlineUserCount() + 60000 */?></span>
                <span class="desc clearfix"> <?/*= Yii::t('app', 'total user online') */?></span>
            </p>

        </div>-->
    </div> <!-- Statistic block -->

    <!--<div class="clearfix"></div>

    <div class="feature-block">

        <div class="feature-left features">
            <div class="sprite-ic message "></div>
            <p>
                <span class="feature-name">    <?/*=Yii::t('app', 'Messages')*/?></span>
                <span class="feature-description">
                    <?/*= Yii::t('app', 'Eveyone gets at least<br/>one message per session'); */?>
                </span>
            </p>
        </div>

        <div class="feature-right features">

            <div class="sprite-ic dialogue"></div>
            <p>
                <span class="feature-name"> <?/*=Yii::t('app', 'Dialogues')*/?></span>
                <span class="feature-description">
                    <?/*= Yii::t('app', 'Every second contacts  <br/> eads to a new dialogue') */?>
                </span>

            </p>

        </div>-->

        <!--            <div class="pull-left features">-->
        <!---->
        <!--                <div class="sprite-ic contact "></div>-->
        <!---->
        <!--                <p>-->
        <!--                    <span class="feature-name">Contacts<!--</span>-->
        <!---->
        <!--                    <span class="feature-description">-->
        <!--                        Active user makes 100  <br/> contacts per day-->
        <!--                    </span>-->
        <!---->
        <!--                </p>-->
        <!---->
        <!--            </div>-->

    </div> <!--Features block-->

</div> <!-- Main div -->