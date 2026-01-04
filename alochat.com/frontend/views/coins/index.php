<?php
/**
 * User: Yusif
 * Date: 7/13/2015
 * Time: 11:09 AM
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use frontend\assets\DiscoveryAsset;
use common\models\User;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Coins services');
DiscoveryAsset::register($this);
\frontend\assets\PhotoUploadAsset::register($this);

 ?>



<div class="profile-page-container profile-page-main" style="padding-top: 0px !important;background-color: transparent;border: none;">

    <div class="row title-block" id="user-filter1">
        <div class="col-md-12">
            <div class="pull-left">   <?= $this->title ?> </div>

        </div>
    </div>
    <div class="row">


        <div class="col-md-4 col-lg-4 col-xs-6 col-sm-6" style="padding-left: 0px; padding-right: 5px;">
            <div style="font-size:18px;padding:10px;background-color: #FFF; border: 1px solid #e3e3e3;min-height: 210px; text-align: center;padding-top: 30px;">
                <?= Yii::t('app','Coins in your acount')?>:
                <br />
                <b style="font-size: 24px;"><?= Yii::$app->user->identity->coins?></b><br />
                <a href="<?= Url::to(["/coins/add-coin"])?>" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> <?= Yii::t('app','Buy coin')?></a>
            </div>
        </div>
        <div class="col-md-8 col-lg-8 col-xs-6 col-sm-6 pull-right"  style="padding-left: 5px; padding-right: 0px;">
            <div style="background-color: #FFF; border: 1px solid #e3e3e3">
                <div style="color:#4D4D4D; font-weight: bold; font-size: 15px;margin: 10px;"></div>
              <p style="margin: 10px;font-size: 14px;">
                  <a href="<?= Url::to(["/coins/change-nick"])?>">
                      <img src="/images/icons/coins/nick.png" width="25" height="25"> <?= Yii::t('app','Change nickname')?>
                  </a>
              </p>
                <p style="margin: 10px;font-size: 14px;">
                    <a href="<?= Url::to(["/coins/online-rating"])?>">
                        <img src="/images/icons/coins/online-rating.png" width="25" height="25"> <?= Yii::t('app','Increase your online position')?>
                    </a>
                </p>
                <p style="margin: 10px;font-size: 14px;">
                    <a href="<?= Url::to(["/coins/set-vip"])?>">
                        <img src="/images/icons/coins/be-vip.png" width="25" height="25"> <?= Yii::t('app','Become a vip user')?>
                    </a>
                </p>
                <p style="margin: 10px;font-size: 14px;">
                    <a href="<?= Url::to(["/coins/send-coin"])?>">
                        <img src="/images/icons/coins/send-coin.png" width="25" height="25"> <?= Yii::t('app', 'Send Coin')?>
                    </a>
                </p>
                <p style="margin: 10px;font-size: 14px;">
                    <a href="<?= Url::to(["/coins/delete-nick"])?>">
                        <img src="/images/icons/coins/delete-login.png" width="25" height="25"> <?= Yii::t('app','Delete account')?>
                    </a>
                </p>

                <!--   <p>Nick dəyiş</p>
                 <p>Nick dəyiş</p>
                 <p>Nick dəyiş</p>
                 <p>Nick dəyiş</p>-->
               <!-- <ul class="nav nav-pills nav-stacked nav-pills-stacked-example">
                    <li role="presentation"><a href="<?/*= Url::to(["/coins/change-nick"])*/?>">-Nick dəyiş</a></li>
                    <li role="presentation"><a href="<?/*= Url::to(["/coins/online-rating"])*/?>">-Onlinede irəlidə görün</a></li>
                    <li role="presentation"><a href="<?/*= Url::to(["/coins/set-vip"])*/?>">-Vip user olmaq</a></li>
                    <li role="presentation"><a href="<?/*= Url::to(["/coins/send-coin"])*/?>">-Bal göndər</a></li>
                    <li role="presentation"><a href="<?/*= Url::to(["/coins/delete-nick"])*/?>">-Loqini silmək (Hesabı bağlamaq)</a></li>
                </ul>-->
           </div>
        </div>
        <div class="clearfix"></div>


    </div>

    <div class="row" style="background-color: #FFF;border: 1px solid #e3e3e3;margin-top: 15px;">
        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
             <table class="table">
                 <caption><?= Yii::t('app','The last point operations')?></caption>
                 <thead>
                    <tr>
                        <th>#</th>
                        <th><?= Yii::t('app','Coin')?></th>
                        <th><?= Yii::t('app','Action')?></th>
                        <th><?= Yii::t('app','Date')?></th>
                    </tr>
                 </thead>
                 <tbody>
                    <?php
                    $i = 1;
                    foreach($logs as $log){?>
                        <tr>
                            <th scope="row"><?= $i;?></th>
                            <td><?php echo $log["type"]==2?'+':"-";?><?= $log["coins"]?></td>
                            <td><?php echo \common\models\CoinLogs::$log_text[$log["text"]] ; ?></td>
                            <td><?php echo $log["date"];?></td>
                        </tr>
                    <?php  $i++; } ?>

                 </tbody>
             </table>
        </div>
    </div>



</div>

