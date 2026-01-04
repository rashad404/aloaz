<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\modal;
use common\models\User;
?>


<div class="col-md-4 col-lg-4 col-sm-3 col-xs-12 profile-left">

    <div class="hidden-xs profile-left-photo">
        <a href="<?= Url::to(["/profile/home/".$user->id])?>"><img src="<?= $profilePhoto?>" class="img-responsive"  style="max-height: 176px;margin: auto"></a>
    </div>

    <div class="hidden-sm hidden-md hidden-lg profile-photo-block-mob">
        <div class="col-xs-6 profile-left-photo-mob">
            <a href="<?= Url::to(["/profile/home/".$user->id])?>">  <img src="<?= $profilePhoto; ?>" class="img-responsive"></a>
        </div>
        <div class="col-xs-6 profile-left-status-mob">
            <div class="profile-left-status">
                <p>
                    <span style="font-weight: bold; font-size: 12px;"><a href="<?= Url::to(["/profile/home/".$user->id])?>"><?= $user["nickname"]; ?></a></span><br /> <?= $user["last_post"]?></p>
            </div>
        </div>
        <div class="clearfix"></div>
        <?php if (!$isOwnProfile and $user["deactive"]==0): ?>

        <div class="col-xs-12 profile-left-status-icons">
            <a class="btn like-btn-mob" id="like-user" onclick="likeUser(<?= $user->id ?>);">

                <div class="like-btn-text liked" style="display: <?= $user->userLiked() ? 'block' : 'none' ?>"><img src="/images/icons/like-btn-ico.png"> <?= Yii::t('app','Bəyənilib')?></div>
                <div class="like-btn-text like" style="display: <?= $user->userLiked() ? 'none' : 'block' ?>"> <img src="/images/icons/like-btn-ico.png"> <?= Yii::t('app','Bəyən')?></div>
            </a>

            <a href="<?= Url::to(['/messages/view', 'u' => $user->id]) ?>#chat" class="btn message-btn-mob">
                <img src="/images/icons/write-btn-ico.png">
                <?= Yii::t('app','Write')?></a>

            <!-- Single button -->
            <div class="btn-group right">
                <a class="btn btn-large dropdown-toggle dropdown-user-mob" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                    <img src="/images/icons/add-friend.png">
                </a>
                <ul class="dropdown-menu dropdown-user-menu">
                    <?php
                    $friendStatus = User::friendStatus($user->id);
                    if($friendStatus == 1){
                        // echo "sorgu gondermisiz ";
                        ?>
                        <li>
                            <a class="cursor" id="reset-friend" onclick="resetFriend(<?= $user['id']; ?>,'<?=   Yii::t('app','Cancel request for friendship');   ?>?');">
                                <img src="/images/icons/sprite/add-friend.png">  <?= Yii::t('app', 'Cancel request for friendship') ?>
                            </a>
                        </li>
                    <?php
                    } elseif($friendStatus == 2){
                        //  echo "sorgunuz gelib  tesdiq gozleyir";
                        ?>
                        <li>
                            <a class="cursor" id="reset-friend" onclick="resetFriend(<?= $user['id']; ?>,'<?=   Yii::t('app','Cancel?');   ?>?');">
                                <img src="/images/icons/sprite/add-friend.png"> <?= Yii::t('app', 'Cancel') ?>
                            </a>
                        </li>
                        <li>
                            <a class="cursor" id="confirm-friend" onclick="confirmFriend(<?= $user['id']; ?>,'<?=   Yii::t('app','Accept');   ?>?');">
                                <img src="/images/icons/sprite/add-friend.png"> <?= Yii::t('app', 'Accept') ?>
                            </a>
                        </li>
                    <?php

                    } elseif($friendStatus == 3) {
                        //  echo "dostsunuz";
                        ?>
                        <li>
                            <a class="cursor" id="reset-friend" onclick="resetFriend(<?= $user['id']; ?>,'<?=   Yii::t('app','Are you sure to remove this user from your friendlist?');   ?>');">
                                <img src="/images/icons/sprite/add-friend.png"> <?= Yii::t('app', 'Cancel friendship') ?>
                            </a>
                        </li>
                    <?php
                    } else {
                        ?>
                        <li>
                            <a class="cursor" id="add-friend" onclick="addFriend(<?= $user->id ?>,'<?=   Yii::t('app','Are you sure to send friend request?'); ?>');">
                                <img src="/images/icons/sprite/add-friend.png"> <?= Yii::t('app', 'Add friend') ?>
                            </a>
                        </li>
                    <?php
                    }
                    ?>                <li>
                        <a class="cursor" id="block-user" onclick="blockUser1(<?= $user->id ?>,'<?= $user->userBlocked() ? Yii::t('app','Are you sure you want to cancel block this user?') : Yii::t('app','Are you sure you want to block this user?'); ?>');">
                            <img src="/images/icons/sprite/block.png">
                            <?= $user->userBlocked() ? 'Blocked' : Yii::t('app','Add block') ?>
                        </a>
                    </li>                <li role="separator" class="divider"></li>
                    <li><a class="cursor" id="report-user" onclick="reportUser(<?= $user->id ?>,'<?= $user->userReported() ? Yii::t('app','Are you sure you want to cancel report this user?') : Yii::t('app','Are you sure you want to report this user?'); ?>');">
                            <img src="/images/icons/sprite/spam.png">
                            <?= $user->userReported() ? 'Şikayəti götür' : Yii::t('app','Şikayət et') ?>
                        </a></li>
                </ul>
            </div>
        </div>

        <div class="clearfix"></div>
        <?php endif; ?>

    </div>
</div>
<div class="col-md-8 col-lg-8 col-sm-9 col-xs-12 profile-center">
    <div class="center-block hidden-xs" id="post">
        <div class="center-block-status">
            <div class="row center-block-status-content">
                <a href="<?= Url::to(["/profile/home/".$user->id])?>">
                    <div class="col-md-11 col-sm-11 col-lg-11 center-block-status-user">
                         <?= $user->nickname;?>
                    </div>
                </a>
                <div class="col-md-1 col-sm-1 col-lg-1">
                    <?php if($user->isOnline()):?>
                        <span class="online">
                                             <div class="status online"></div>
                                             </span>
                    <?php endif; ?>
                </div>
                <div class="col-md-12 center-block-status-text">
                    <?= $user->last_post ?>
                </div>
            </div>

        </div>
    </div>
    <?php
     if (!$isOwnProfile and $user["deactive"]==0): ?>
         <div class="center-block col-md-12 hidden-xs user-buttons">
             <a class="btn like-btn" id="like-user" onclick="likeUser(<?= $user->id ?>);">
                 <div class="profile-like-ic"></div>
                 <div class="like-btn-text liked" style="display: <?= $user->userLiked() ? 'block' : 'none' ?>"><?= Yii::t('app','Bəyənilib')?></div>
                 <div class="like-btn-text like" style="display: <?= $user->userLiked() ? 'none' : 'block' ?>"><?= Yii::t('app','Bəyən')?></div>
             </a>
             <a href="<?= Url::to(['/messages/view', 'u' => $user->id]) ?>#chat" class="btn message-btn" >
                 <div class="profile-message-ic"></div>
                 <div class="message-btn-text"><?= Yii::t('app','Write')?></div>


             </a>
            <!-- Single button -->
             <div class="btn-group profile-btn pull-right">
                 <a class="btn btn-large dropdown-toggle dropdown-user" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                     <img src="/images/icons/add-friend.png">
                 </a>
                 <ul class="dropdown-menu dropdown-user-menu">
                     <?php
                     $friendStatus = User::friendStatus($user->id);
                     if($friendStatus == 1){
                         // echo "sorgu gondermisiz ";
                         ?>
                         <li>
                             <a class="cursor" id="reset-friend" onclick="resetFriend(<?= $user['id']; ?>,'<?=   Yii::t('app','Cancel request for friendship');   ?>?');">
                                 <img src="/images/icons/sprite/unfollow1.png">  <?= Yii::t('app', 'Cancel request for friendship') ?>
                             </a>
                         </li>
                     <?php
                     } elseif($friendStatus == 2){
                         //  echo "sorgunuz gelib  tesdiq gozleyir";
                         ?>
                         <li>
                             <a class="cursor" id="reset-friend" onclick="resetFriend(<?= $user['id']; ?>,'<?=   Yii::t('app','Cancel?');   ?>?');">
                                 <img src="/images/icons/sprite/unfollow2.png"> <?= Yii::t('app', 'Cancel') ?>
                             </a>
                         </li>
                         <li>
                             <a class="cursor" id="confirm-friend" onclick="confirmFriend(<?= $user['id']; ?>,'<?=   Yii::t('app','Accept');   ?>?');">
                                 <img src="/images/icons/sprite/add-friend.png"> <?= Yii::t('app', 'Accept') ?>
                             </a>
                         </li>
                     <?php

                     } elseif($friendStatus == 3){
                         //  echo "dostsunuz";
                         ?>
                         <li>
                             <a class="cursor" id="reset-friend" onclick="resetFriend(<?= $user['id']; ?>,'<?=   Yii::t('app','Are you sure to remove this user from your friendlist?');   ?>');">
                                 <img src="/images/icons/sprite/add-friend.png"> <?= Yii::t('app', 'Cancel friendship') ?>
                             </a>
                         </li>
                     <?php
                     } else {
                         ?>
                         <li>
                             <a class="cursor" id="add-friend" onclick="addFriend(<?= $user->id ?>,'<?=   Yii::t('app','Are you sure to send friend request?'); ?>');">
                                 <img src="/images/icons/sprite/add-friend.png"> <?= Yii::t('app', 'Add friend') ?>
                             </a>
                         </li>
                     <?php
                     }
                     ?>


                     <li>
                         <a class="cursor" id="block-user" onclick="blockUser1(<?= $user->id ?>,'<?= $user->userBlocked() ? Yii::t('app','Are you sure you want to cancel block this user?') : Yii::t('app','Are you sure you want to block this user?'); ?>');">
                             <img src="/images/icons/sprite/block.png">
                             <?= $user->userBlocked() ? 'Blocked' : Yii::t('app','Add block') ?>
                         </a>
                     </li>
                     <li role="separator" class="divider"></li>
                     <li><a class="cursor" id="report-user" onclick="reportUser(<?= $user->id ?>,'<?= $user->userReported() ? Yii::t('app','Are you sure you want to cancel report this user?') : Yii::t('app','Are you sure you want to report this user?'); ?>');">
                             <img src="/images/icons/sprite/spam.png">
                             <?= $user->userReported() ? 'Şikayəti götür' : Yii::t('app','Şikayət et') ?>
                         </a></li>
                 </ul>
             </div>

        </div>

    <?php elseif($user["deactive"]==1):
     echo '<div class="alert alert-danger">Bu istifadəçi profilini bağlayıb</div>';
     endif; ?>
    <div class="clearfix"></div>
</div>


