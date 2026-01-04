<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 04.05.2015
 * Time: 18:07
 */
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\User;

?>


<div class="row" style="padding-left: 10px;">

    <?php
    $i=1;$st= '';
    if ($users): ?>

        <?php foreach ($users as $user): ?>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                <div class="row new-user-padding" style="<?php if($i%2==1) echo 'padding-right: 10px;'; else echo 'padding-left:10px;';?>">
                    <div class="new-user-block">
                        <div style="float: left">
                            <a href="<?= Url::to(['/profile/index/', 'id' => $user['id']])?>">
                                <img class="pull-left" src="<?= $user['profile_photo']?Url::base() . $user['profile_photo'] : Url::base() . Yii::$app->params['defaultProfilePicture_'.$user['sex']] ?>" data-sec-id="35" id="2686" height="92" width="92">
                            </a>
                        </div>
                        <div class="new-user-block-about">
                            <a href="<?= Url::to(['/profile/index/', 'id' => $user['id']])?>">
                                <div>

                                    <div class="new-user-block-name">
                                        <?php if (\common\models\User::isOnlineWithActivity($user['last_activity'])){ ?>

                                            <span class="online" title="Online"><div class="status online"></div> </span>
                                        <?php } else{
                                            ?>
                                            <span class="online2" title="Offline"><div class="status2 online2"></div> </span>

                                        <?php
                                        } ?>
                                        <?= mb_substr(Html::encode($user['nickname'])."", 0, 18,'utf-8' ) ?>

                                    </div>

                                    <p class="new-user-block-city">
                                        <?= $user['age'] . ' ' .Yii::t('app', 'years')?><?=isset($user['city_name'])?', ' . $user['city_name'] : '' ?>
                                    </p>

                                    <?php
                                        $friendStatus = User::friendStatus($user['id']);
                                        if($friendStatus == 1){
                                           // echo "sorgu gondermisiz ";
                                        ?>
                                            <button id="reset-friend" onclick="resetFriend(<?= $user['id']; ?>,'<?=   Yii::t('app','Dostluqdan cixarmaq istediyinize eminsiniz ?');   ?>');" type="button"
                                                    class="btn btn-danger btn-xs">
                                                <?= Yii::t('app', 'Cancel request for friendship') ?>
                                            </button>
                                        <?php
                                        } elseif($friendStatus == 2){
                                          //  echo "sorgunuz gelib  tesdiq gozleyir";
                                            ?>
                                            <button id="reset-friend" onclick="resetFriend(<?= $user['id']; ?>,'<?=   Yii::t('app','Cancel');   ?>?');" type="button"
                                                    class="btn btn-danger btn-xs">
                                                 <?= Yii::t('app', 'Cancel') ?>
                                            </button>
                                            <button id="confirm-friend" onclick="confirmFriend(<?= $user['id']; ?>,'<?=   Yii::t('app','Accept');   ?>?');" type="button"
                                                    class="btn btn-success btn-xs">
                                                 <?= Yii::t('app', 'Accept') ?>
                                            </button>
                                        <?php

                                        } else {
                                          //  echo "dostsunuz";
                                            ?>
                                            <button id="reset-friend" onclick="resetFriend(<?= $user['id']; ?>,'<?=   Yii::t('app','Are you sure to remove this user from your friendlist?');   ?>');" type="button"
                                                    class="btn btn-danger btn-xs">
                                                 <?= Yii::t('app', 'Cancel friendship') ?>
                                            </button>
                                        <?php
                                        }
                                    ?>

                             <!--       <?php
/*                                        if($user['ok'] == 0 and $user["user_2"]==Yii::$app->user->id){
                                            */?>
                                            <button id="add-friend" onclick="addFriend(<?/*= $user['id'] */?>,'<?/*= Yii::$app->user->identity->userIsFriend() ? Yii::t('app','Are you send friend request?') : Yii::t('app','Are you send friend request?'); */?>');" type="button"
                                                    class="btn btn-primary btn-xs">
                                                <i class="glyphicon glyphicon-plus-sign
                                                <?/*= Yii::$app->user->identity->userIsFriend() ? 'text-danger' : '' */?>

                                                "></i> <?/*= Yii::t('app', 'Add friend') */?>
                                                                    </button>
                                            <?php
/*                                        } elseif($user['ok'] ==0 and $user['user_1'] == Yii::$app->user->id) {
                                            */?>

                                    <p class="user-block-last-post">
                                        <?/*= "Sorgu gonderilmisdir tesdiq gozleyir" */?>
                                    </p>
                                           --><?php /* } */?>
                                </div>
                            </a>

                        </div>
                        <div class="new-user-block-send">
                            <a href="<?= Url::to(['/messages/view', 'u' => $user['id']]) ?>#chat">
                                <img src="/images/icons/send-message.png" style="margin-top: 10px;">
                            </a>
                            <a href="<?= Url::to(['/profile/gift/', 'id' => $user['id']])?>">
                                <img src="/images/icons/send-gift.png" style="margin-top: 18px;">
                            </a>
                        </div>
                        <div style="float: right;width: 20px;min-height: 80px; margin-top: 10px;margin-right: 5px;padding-left: 5px;">
                            <?php if($user['sex']==User::SEX_MAN) $sex_icon = 'man-icon.png';
                            elseif($user['sex'] == User::SEX_WOMAN) $sex_icon = 'woman-icon.png'?>

                            <img src="/images/icons/<?= $sex_icon?>"><br /><br /><br />

                        </div>

                    </div>
                </div>
            </div>
            <?php $i++;?>

        <?php endforeach; ?>
    <?php else: ?>
        <div class="container">
            <p class="text-danger"><?= Yii::t('app', 'Nobody found.') ?></p>
        </div>
    <?php endif; ?>
</div>