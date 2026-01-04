<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\modal;
use common\models\User;
?>

<div class="hidden-xs profile-left-photo">
    <div class="photo-prw">
        <img  src="<?= $profilePhoto?>"
              data-sec-id="<?= $user["id"] ?>"
              id="<?= $user['profile_photo_id'] ?>" class="img-responsive"/>
    </div>
 </div>

<div class="hidden-sm hidden-md hidden-lg profile-photo-block-mob">
    <div class="col-xs-12 profile-left-photo-mob">
        <img src="<?= $profilePhoto; ?>" class="img-responsive">
    </div>
    <div class="col-xs-12 profile-left-status-mob">
        <div class="profile-left-status">
            <p>
                <span style="font-weight: bold; font-size: 12px;"><?= $user["nickname"]; ?></span><br /> <?= $user["last_post"]?></p>
        </div>
    </div>
    <div class="clearfix"></div>
    <?php if (!$isOwnProfile): ?>

    <div class="col-xs-12 profile-left-status-icons">
         <a class="btn like-btn-mob" id="like-user" onclick="likeUser(<?= $user->id ?>);">

             <div class="like-btn-text liked" style="display: <?= $user->userLiked() ? 'block' : 'none' ?>"><img src="/images/icons/like-btn-ico.png"> Liked</div>
             <div class="like-btn-text like" style="display: <?= $user->userLiked() ? 'none' : 'block' ?>"> <img src="/images/icons/like-btn-ico.png"> Like</div>
          </a>

        <a href="<?= Url::to(['/messages/view', 'u' => $user->id]) ?>#chat" class="btn message-btn-mob">
            <img src="/images/icons/write-btn-ico.png">
            <?= Yii::t('app','Write')?></a>

        <!-- Single button -->
        <div class="btn-group right" style="float: right">
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

<div class="hidden-lg hidden-md  hidden-sm profile-user-details-block">
    <div class="row  margin-0">
        <div class="col-xs-12 border-block padding-0">
            <div class="row profile-user-details-row">

                <div class="col-xs-6 user-details-left" ><?= Yii::t('app','Name'); ?></div>
                <div class="col-xs-6 user-details-right"><?php echo $user->full_name; ?></div>
            </div>
            <div class="row profile-user-details-row">

                <div class="col-xs-6 user-details-left" ><?= Yii::t('app','Sex'); ?></div>
                <div class="col-xs-6 user-details-right"><?php echo $user->getSexValue($user["sex"]); ?></div>
            </div>

            <div class="row profile-user-details-row">
                <div class="col-xs-6 user-details-left"><?= Yii::t('app','Location')?></div>
                <div class="col-xs-6 user-details-right"><?php echo \common\models\Country::getCountryName($user->country_id);?>,<?php echo \common\models\City::getCityName($user->city_id);?></div>
            </div>

            <div class="row profile-user-details-row">
                <div class="col-xs-6 user-details-left"><?= Yii::t('app','Like count')?></div>
                <div class="col-xs-6 user-details-right likeCount"><?= $user->getLikeCountUsers($user->id);?></div>
            </div>

            <div class="row profile-user-details-row">
                <div class="col-xs-6 user-details-left"><?= Yii::t('app','Last login date')?></div>
                <div class="col-xs-6 user-details-right">
                    <?php
                    if ($user->isOnline()) {
                        echo Yii::t('app','Online');
                        echo '<span class="online">
                                                    <div class="status online"></div>
                                                       </span>';
                    } else {
                        echo date("d-m-Y H:i",$user->last_activity);
                    }
                    ?>
                </div>
            </div>

            <div class="row profile-user-details-row">
                <div class="col-xs-6 user-details-left"><?= Yii::t('app','Register date')?></div>
                <div class="col-xs-6 user-details-right"><?= date("d-m-Y",$user->created_at);?></div>
            </div>

            <div class="row profile-user-details-row">
                <div class="col-xs-6 user-details-left"><?= Yii::t('app','Register Number (ID)'); ?></div>
                <div class="col-xs-6 user-details-right"> 	<?php echo $user->id; ?></div>
            </div>

            <div class="row profile-user-details-row-last">
                <div class="col-xs-6 user-details-left"><?= Yii::t('app','About me')?></div>
                <div class="col-xs-6 user-details-right">
                    <?= $user->about;?>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="left-block">
    <div class="left-block-title">
        <a href="<?= Url::to(['/profile/photos/'.$user["id"]])?>"><?= Yii::t('app','Photos')?> <?php echo c>0?' - '.$photosCount:'';?></a>
    </div>
    <div class="left-block-content">
        <div class="row user-left-gallery-block">
            <?php
            if($photosCount>0) {
                foreach ($photos as $photo):?>
                    <div class="left-gallery-img">
                        <a class="photo-prw">
                            <img src="<?= Url::base() . $photo["path"] ?>"
                                 data-sec-id="<?= $user["id"] ?>"
                                 id="<?= $photo["id"] ?>" class="img-responsive"/>
                        </a>
                    </div>
                <?php endforeach;
            }else {
                echo Yii::t('app','Photos')." ".Yii::t('app','not found');
            }?>

            <div class="clearfix"></div>
        </div>

    </div>
</div>

<div class="left-block">
    <div class="left-block-title">

        <a href="<?= Url::to(['/profile/friends/'.$user["id"]])?>"> <?= Yii::t('app','Friends')?><?php echo  $friendsCount>0?' - '.$friendsCount:'';?></a>
    </div>
    <div class="left-block-content">
        <div class="row user-left-gallery-block">
            <?php
            if($friendsCount>0) {
            foreach($friends as $friend):?>

                <div class="left-gallery-img">
                     <a href="<?= Url::to(['/profile/index/', 'id' => $friend['id']])?>"><img src="<?= $friend["profile_photo"]?>" class="img-responsive"></a>

                    <p class="left-gallery-img-text"><?= $friend["nickname"]?></p>

                </div>
            <?php endforeach;
            }else {
                echo Yii::t('app','Friends')." ".Yii::t('app','not found');
            } ?>

            <div class="clearfix"></div>
        </div>

    </div>
</div>

<div class="left-block">
    <div class="left-block-title">
        <a href="<?= Url::to(['/gift/'.$user["id"]])?>"><?= Yii::t('app','Gifts');?><?php echo  $giftsCount>0?' - '.$giftsCount:'';?></a>
    </div>
    <div class="left-block-content">
        <div class="row user-left-gallery-block">
            <?php
            if($giftsCount>0) {
                foreach ($gifts as $gift):
                    ?>
                    <a href="<?= Url::to(['/gift/' . $user["id"]]) ?>">
                        <div class="left-gallery-img"><img src="<?= $gift["icon"]; ?>" width="55" height="55"
                                                           class="img-responsive"></div>
                    </a>

                <?php endforeach;
            }else {
                echo Yii::t('app','Gifts').' '.Yii::t('app','not found');
            }
            ?>
            <div class="clearfix"></div>
        </div>

    </div>
</div>

<!-- Hidden preview block -->
<div id="photo_preview" style="display:none">

    <div class="photo_wrp">

                <span class="cancel ">

                    <i class="glyphicon glyphicon-remove-circle"></i>
                </span>
        <div class="pleft">&lt;</div>

        <div class="pright">&gt;</div>

        <div class="clearfix"></div>
    </div>
</div>

