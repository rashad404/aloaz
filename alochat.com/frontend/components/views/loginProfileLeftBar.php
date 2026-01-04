<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\modal;
use common\models\User;
?>
<?php
echo $this->render('/site/partials/modals/login_alert_modal.php');
?>

<div class="col-md-4 col-lg-4 col-sm-3 col-xs-12 profile-left">

    <div class="hidden-xs profile-left-photo">
        <img src="<?= $profilePhoto; ?>" class="img-responsive"  style="max-height: 176px;margin: auto">
    </div>

    <div class="hidden-sm hidden-md hidden-lg profile-photo-block-mob">
        <div class="col-xs-3 profile-left-photo-mob">
              <img src="<?= $profilePhoto; ?>" class="img-responsive">
        </div>
        <div class="col-xs-9 profile-left-status-mob">
            <div class="profile-left-status">
                <p>
                    <span style="font-weight: bold; font-size: 12px;"><a href="<?= Url::to(["/profile/home/".$user->id])?>"><?= $user["nickname"]; ?></a></span><br /> <?= $user["last_post"]?></p>
            </div>
        </div>
        <div class="clearfix"></div>

        <?php if (!$isOwnProfile): ?>

        <div class="col-xs-12 profile-left-status-icons">
            <a  class="btn like-btn-mob" id="like-user"   data-toggle="modal" data-target="#login_alert_modal">

                 <div class="like-btn-text like"> <img src="/images/icons/like-btn-ico.png"> Like</div>
            </a>



            <a class="cursor btn message-btn-mob" data-toggle="modal" data-target="#login_alert_modal">
                <img src="/images/icons/write-btn-ico.png">
                <?= Yii::t('app','Write')?></a>

            <!-- Single button -->
            <div class="btn-group right">
                <a class="btn btn-large dropdown-user-mob" data-toggle="modal" data-target="#login_alert_modal">

                    <img src="/images/icons/add-friend.png">
                </a>

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
                <a data-toggle="modal" data-target="#login_alert_modal">
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
     if (!$isOwnProfile): ?>
         <div class="center-block col-md-12 hidden-xs user-buttons">
             <a class="btn like-btn" id="like-user" data-toggle="modal" data-target="#login_alert_modal">
                 <div class="profile-like-ic"></div>
                 <div class="like-btn-text liked">Like</div>
              </a>
             <a data-toggle="modal" data-target="#login_alert_modal" class="btn message-btn" >
                 <div class="profile-message-ic"></div>
                 <div class="message-btn-text"><?= Yii::t('app','Write')?></div>


             </a>
            <!-- Single button -->
             <div class="btn-group profile-btn pull-right">
                 <a class="btn btn-large dropdown-toggle dropdown-user" data-toggle="modal" data-target="#login_alert_modal">

                     <img src="/images/icons/add-friend.png">
                 </a>

             </div>

        </div>

    <?php endif; ?>
    <div class="clearfix"></div>
</div>


