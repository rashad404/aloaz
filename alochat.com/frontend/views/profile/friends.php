<?php
use frontend\assets\PhotoUploadAsset;
use yii\bootstrap\ActiveForm;
use common\models\User;
use yii\helpers\Url;
use yii\helpers\Html;
PhotoUploadAsset::register($this);
$this->title = $userModel["nickname"];
?>

<div class="row">


    <div class="col-md-12" style="background-color: #f5f5f5">
        <div class="row profile-title-block" id="user-filter1">
            <div class="col-md-12">
                <ul class="nav nav-tabs profile-nav-tabs">
                    <li role="presentation"><a href="<?php echo Url::to(['/profile/timeline/'.$userModel["id"]])?>">Timeline</a></li>
                    <li role="presentation"><a href="<?php echo Url::to(['/profile/photos/'.$userModel["id"]])?>"><?= Yii::t('app','Photos')?></a></li>
                    <li role="presentation"   class="active"><a href="<?php echo Url::to(['/profile/friends/'.$userModel["id"]])?>"><?= Yii::t('app','Friends')?></a></li>
                    <li role="presentation"><a href="<?php echo  Url::to(['/profile/gifts/'.$userModel["id"]])?>"><?= Yii::t('app','Gifts')?></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="center-block col-md-12" style="background-color: #f5f5f5;border: 0px !important;">

        <!--------------->
        <div class="row" >

            <?php
            $i=1;$st= '';
            if ($users): ?>

                <?php foreach ($users as $user): ?>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">

                        <div class="row new-user-padding">
                            <div class="new-user-block" style="margin: 5px 5px 5px 5px;">
                                <div style="float: left">
                                    <a href="<?= Url::to(['/profile/index/', 'id' => $user['id']])?>">
                                        <img class="pull-left" src="<?= $user['profile_photo']?Url::base() . $user['profile_photo'] : Url::base() . Yii::$app->params['defaultProfilePicture_'.$user['sex']] ?>" data-sec-id="35" id="2686" height="92" width="92">
                                    </a>
                                </div>
                                <div class="new-friend-block-about">
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
                                            <div class="user-block-last-post">
                                                <?php
                                                if($isOwnProfile):
                                                    $friendStatus = User::friendStatus($user["id"]);
                                                    if($friendStatus == 1){
                                                        // echo "sorgu gondermisiz ";
                                                        ?>
                                                             <a class="cursor" id="reset-friend" onclick="resetFriend(<?= $user['id']; ?>,'<?=   Yii::t('app','Cancel request for friendship');   ?>?');">
                                                                <img src="/images/icons/sprite/add-friend.png">  <?= Yii::t('app', 'Cancel request for friendship') ?>
                                                            </a>
                                                     <?php
                                                    } elseif($friendStatus == 2){
                                                        //  echo "sorgunuz gelib  tesdiq gozleyir";
                                                        ?>
                                                             <a class="cursor" id="reset-friend" onclick="resetFriend(<?= $user['id']; ?>,'<?=   Yii::t('app','Cancel?');   ?>?');">
                                                                <img src="/images/icons/sprite/add-friend.png"> <?= Yii::t('app', 'Cancel') ?>
                                                            </a>

                                                            <a class="cursor" id="confirm-friend" onclick="confirmFriend(<?= $user['id']; ?>,'<?=   Yii::t('app','Accept');   ?>?');">
                                                                <img src="/images/icons/sprite/add-friend.png"> <?= Yii::t('app', 'Accept') ?>
                                                            </a>

                                                    <?php

                                                    } elseif($friendStatus == 3) {
                                                        //  echo "dostsunuz";
                                                        ?>
                                                             <a class="cursor" id="reset-friend" onclick="resetFriend(<?= $user['id']; ?>,'<?=   Yii::t('app','Are you sure to remove this user from your friendlist?');   ?>');">
                                                                <img src="/images/icons/sprite/add-friend.png"> <?= Yii::t('app', 'Cancel friendship') ?>
                                                            </a>

                                                    <?php
                                                    } else {
                                                        ?>
                                                            <a class="cursor" id="add-friend" onclick="addFriend(<?= $user->id ?>,'<?=   Yii::t('app','Are you sure to send friend request?'); ?>');">
                                                                <img src="/images/icons/sprite/add-friend.png"> <?= Yii::t('app', 'Add friend') ?>
                                                            </a>
                                                    <?php
                                                    }
                                                else:
                                                    echo mb_substr(Html::encode($user['last_post'])."", 0, 55,'utf-8' ) ;
                                                endif;
                                                ?>

                                             </div>
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
        <!--------------->


        <?php
        if ($pages) {
            // display pagination
            echo '<div class="text-center">';

            echo \yii\widgets\LinkPager::widget([
                'pagination' => $pages,
            ]);
            echo '</div>';
        }
        ?>
    </div>

</div>