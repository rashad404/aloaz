<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\modal;
?>


<div class="hidden-xs">
    <a class="link-default" href="<?= Url::to(['profile/index/', 'id' => Yii::$app->user->id]) ?>">
        <div class="row">
            <div class="col-md-3 col-sm-4 col-lg-3">
                <img width="50" height="50" class="img-circle" style="margin-left: 15px;"
                     src="<?= Yii::$app->user->identity->profile_photo
                         ? Url::base() . Yii::$app->user->identity->profile_photo
                         : Url::base() . Yii::$app->params['defaultProfilePicture_'.Yii::$app->user->identity->sex] ?>"></div>
            <div class="col-md-9 col-sm-8 col-lg-9" style="margin-top: 6px;color: #37474f;font-weight:bold;font-size: 14px;font-family: Arial, Helvetica, Sans-Serif">
                <?php echo Yii::$app->user->identity->full_name; ?><br />
                <span style="font-size: 12px;font-weight: 200">
                    <span style="color: #B0BEC5">@</span> <?= Yii::$app->user->identity->nickname;?></span>
            </div>

        </div>




    </a>
    <hr style="margin-top: 10px;margin-bottom: 10px;" />
    <p style="text-align: center; font-size: 13px; "><?= Yii::t('app','Your Activity')." : ".\common\models\UserActivity::getActivityUser()."%";?></p>

    <?php if(\common\models\UserActivity::getActivityUser() <= 100){
        $userLevel = \common\models\UserActivity::getActivityUser();
    }else {
        $userLevel = 100;
    }?>
    <div class="progress" style="margin: 10px 5px 5px 10px;height: 14px;">
        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?=$userLevel?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$userLevel?>%">
            <span class="sr-only"><?=$userLevel?>% Complete</span>
        </div>

    </div>
    <a href="<?= Url::to(["/coins"])?>">
        <div class="btn btn-sm btn-default col-md-11 coin-button" ><!--data-toggle="modal" data-target="#coins-modal"-->
            <span style="font-weight: bold;font-size: 14px;"><img src="/images/icons/diamond.png" width="28" height="19" />
                <?php echo Yii::$app->user->identity->coins." ".Yii::t('app', 'Coins'); ?>
            </span>
        </div>
    </a>
</div>

<table class="table table-submenu hidden-xs" style="border: none !important;margin-bottom: 20px">
    <tr style="border: none !important;">
        <td style="width: 24px;">
            <a href="<?= Url::to(['/profile/visitors/']) ?>">
                <div style="border-radius: 50%; width: 20px;height: 20px;background: #67b638;">
                    <img src="/images/icons/visitors.png" width="14" height="10" style="margin-top:5px; margin-left:3px;  ">
                </div>
            </a>
        </td>
        <td><a href="<?= Url::to(['/profile/visitors/']) ?>" style="display: block"><?= Yii::t('app', 'Visitors') ?>    </a></td>
        <td><a href="<?= Url::to(['/profile/visitors/']) ?>"><span class=" count empty badge"><?= $visitorCount > 0 ? "+" . $visitorCount : '' ?></span> </a></td>

    </tr>
    <tr>
        <td>
            <a class="fast" href="<?= Url::to(['/profile/liked/']) ?>">
                <div style="border-radius: 50%; width: 20px;height: 20px;background: #f067ae;">
                    <img src="/images/icons/vip-likes.png" width="10" height="10" style="margin-top:5px; margin-left:5px;  ">
                </div>
            </a>
        </td>
        <td><a class="fast" href="<?= Url::to(['/profile/liked/']) ?>" style="display: block">    <?= Yii::t('app', 'I like') ?>     </a></td>
        <td><a class="fast" href="<?= Url::to(['/profile/liked/']) ?>"> <span class="count empty"><?= $likedUserCount ?></span> </a></td>
    </tr>

    <tr>
        <td>
            <a class="fast" href="<?= Url::to(['/profile/like/']) ?>">
                <div style="border-radius: 50%; width: 20px;height: 20px;background: #e3842a;">
                    <img src="/images/icons/like.png" width="10" height="12" style="margin-top:3px; margin-left:5px;">
                </div>
            </a>
        </td>
        <td><a class="fast" href="<?= Url::to(['/profile/like/']) ?>" style="display: block"><?= Yii::t('app', 'Likes') ?></td>
        <td><a class="fast" href="<?= Url::to(['/profile/like/']) ?>"><span class="count empty badge"><?= $likeCount > 0 ? "+" . $likeCount : '' ?></span> </a></td>
    </tr>
    <tr>
        <td>
            <a class="fast" href="<?= Url::to(['/profile/mutual-likes/']) ?>">
                <div style="border-radius: 50%; width: 20px;height: 20px;background: #24a2f1;">
                    <img src="/images/icons/mutual-likes.png" width="14" height="13" style="margin-top:3px; margin-left:3px;  ">
                </div>
            </a>
        </td>
        <td><a class="fast" href="<?= Url::to(['/profile/mutual-likes/']) ?>" style="display: block"><?= Yii::t('app', 'Mutual likes') ?></a></td>
        <td><a class="fast" href="<?= Url::to(['/profile/mutual-likes/']) ?>"><span class="count empty "><?= $mutualLikeCount ?></span> </a></td>
    </tr>
    <tr>
        <td>
            <a class="fast" href="<?= Url::to(['/gift/'.Yii::$app->user->id]) ?>">
                <div style="border-radius: 50%; width: 20px;height: 20px;background: #e83942;">
                    <img src="/images/icons/gifts.png" width="10" height="10" style="margin-top:5px; margin-left:5px;  ">
                </div>
            </a>
        </td><!--<span data-val="1" class="new-message-count">1</span>-->
        <td><a class="fast" href="<?= Url::to(['/gift/'.Yii::$app->user->id]) ?>"><?= Yii::t('app', 'My gifts') ?></a></td>
        <td><a class="fast" href="<?= Url::to(['/gift/'.Yii::$app->user->id]) ?>"><span class="count empty badge"><?= $giftCount > 0 ? "+" . $giftCount : '' ?></span></a></td>
    </tr>
         <tr>
            <td>
                <a class="fast" href="<?= Url::to(['/profile/friend']) ?>" style="display: block">
                    <div style="border-radius: 50%; width: 20px;height: 20px;background: #E3BB1A ;">
                        <img src="/images/icons/friend-icon.png" width="14" height="14" style="margin-top:2px; margin-left:3px;  ">
                    </div>
                </a>
            </td><!--<span data-val="1" class="new-message-count">1</span>-->
            <td><a class="fast" href="<?= Url::to(['/profile/friend']) ?>" style="display: block"><?= Yii::t('app', 'Friends') ?></a></td>
            <td><a class="fast" href="<?= Url::to(['/profile/friend']) ?>"><span class="count empty badge"><?= $newFriendCount > 0 ? "+" . $newFriendCount : '' ?></span></a></td>
        </tr>
 </table>

<ul class="hidden-lg hidden-sm hidden-md">
    <li>
        <a href="<?= Url::to(['/profile/visitors/']) ?>">
            <p> <span class="nav-icons nav-visitor-ic"></span> <?= Yii::t('app','Visitors')?> <span class="count empty badge"><?= $visitorCount > 0 ? "+" . $visitorCount : '' ?></span> </p>
        </a>
    </li>
    <li>
        <a href="<?= Url::to(['/profile/liked/']) ?>">
            <p> <span class="nav-icons nav-viplike-ic"></span>  <?= Yii::t('app', 'I like') ?>  <span class="count empty badge"><?= $likedUserCount ?></span> </p>
        </a>
    </li>
    <li>
        <a href="<?= Url::to(['/profile/like/']) ?>">
            <p> <span class="nav-icons nav-like-ic"></span>  <?= Yii::t('app', 'Likes') ?>  <span class="count empty badge"><?= $likeCount > 0 ? "+" . $likeCount : '' ?></span> </p>
        </a>
    </li>
    <li>
        <a href="<?= Url::to(['/profile/mutual-likes/']) ?>">
            <p> <span class="nav-icons nav-mutual-like-ic"></span>  <?= Yii::t('app', 'Mutual likes') ?> <span class="count empty badge"><?= $mutualLikeCount ?></span> </p>
        </a>
    </li>
    <li>
        <a href="<?= Url::to(['/gift/'.Yii::$app->user->id]) ?>">
            <p> <span class="nav-icons nav-gift-ic"></span>  <?= Yii::t('app', 'My gifts') ?><span class="count empty badge"><?= $giftCount > 0 ? "+" . $giftCount : '' ?></span></p>
        </a>
    </li>
    <li>
        <a href="<?= Url::to(['/profile/friend']) ?>">
            <p> <span class="nav-icons nav-friend-ic"></span>  <?= Yii::t('app', 'Friends') ?><span class="count empty badge"><?= $newFriendCount > 0 ? "+" . $newFriendCount : '' ?></span></p>
        </a>
    </li>
</ul>

