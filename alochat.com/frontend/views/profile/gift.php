<?php
/* @var $this yii\web\View */
use yii\helpers\Url;
use frontend\assets\PhotoUploadAsset;
use common\models\User;

PhotoUploadAsset::register($this);


$this->title = $user->full_name;
echo $this->render('partials/gift_modal');

?>
<div class="profile-page-container">


    <div class="row">

        <div class="container">
            <div class="photo">
                <img width="120" height="120" class="pull-left img-rounded"
                     src="<?= $user->profile_photo
                         ? Url::base() . $user->profile_photo
                         : Url::base() . Yii::$app->params['defaultProfilePicture_'.$user->sex] ?>"
                     data-sec-id="<?= $user->id ?>"
                     id="<?= $user->profile_photo_id ?>"
                    >
            </div>


            <div style="margin-left: 10px;  " class="pull-left">

                <span class="profile-name"><a href="<?= Url::to('/u/'.$user->id)?>"><?= $user->full_name ?></a></span>
                <span class="profile-page-meta"><?= $user->age ?> <?= Yii::t('app', 'years') ?>
                    ,&nbsp;<?= $user->city ? $user->city->name : '' ?></span>
                <?php if ($user->isOnline()): ?>
                    <span class="online">
                    <div class="status online"></div>
                       </span>
                <?php endif ?>

                <?php if ($isOwnProfile): ?>
                    <p>
                        <div class="clearfix"></div>
                        <a class="btn btn-primary btn-sm" href="<?= Url::to(['profile/settings']) ?>"> <?= Yii::t('app', 'Edit') ?></a>
                    </p>

                <?php endif ?>
                <?php if (!$isOwnProfile): ?>

                    <hr id="sendgift"/>

                    <button id="like-user" onclick="likeUser(<?= $user->id ?>);" type="button"
                            class="btn btn-primary btn-sm">
                        <i class="glyphicon glyphicon-heart
                        <?= $user->userLiked() ? 'text-danger' : '' ?>

                        "></i> <?= Yii::t('app', 'Like') ?>
                    </button>

                    <a href="<?= Url::to(['/messages/view', 'u' => $user->id]) ?>#chat" type="button"
                       class="btn btn-primary btn-sm">
                        <i class="glyphicon glyphicon-envelope"></i> <?= Yii::t('app', 'Write') ?>
                    </a>
                    <button id="block-user" onclick="blockUser1(<?= $user->id ?>,'<?= $user->userBlocked() ? Yii::t('app','Are you sure you want to cancel block this user?') : Yii::t('app','Are you sure you want to block this user?'); ?>');" type="button"
                            class="btn btn-primary btn-sm">
                        <i class="glyphicon glyphicon-ban-circle
                        <?= $user->userBlocked() ? 'text-danger' : '' ?>

                        "></i> <?= Yii::t('app', 'Add block') ?>
                    </button>

                <?php endif ?>

            </div>

        </div>
    </div>

    <div id="modal_id"></div>


    <?php if(!$isOwnProfile) {?>
    <div  id="usergifts">
        <h3><?php echo Yii::t('app','Gifts'); ?></h3>
        <h5><?= Yii::t('app','Choose one of the following gifts')?></h5>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">

            <?php
            $cat_i = 1;
                foreach($categories as $category){
            ?>
                    <li role="presentation" class="<?php echo $cat_i==1?'active':'';?>"><a href="#giftTab<?php echo $category["id"];?>" aria-controls="home" role="tab" data-toggle="tab"><?= $category["name"]; ?></a></li>
            <?php
                    $cat_i++;
                }
            ?>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">

            <?php
            $cat_i = 1;
            foreach($categories as $category){
                ?>
                <div role="tabpanel" class="tab-pane <?php echo $cat_i==1?'active':''; ?>" id="giftTab<?php echo $category["id"];?>" style="padding-top: 10px;padding-left: 10px;">
                    <div class="row">
                        <?php

                        foreach($gifts as $gift) {

                            if ($gift['category_id'] == $category['id']){
                                 ?>
                                <div class="col-xs-6 col-md-2 col=lg-2 col-sm-3">
                                <a  class="thumbnail giftId" style="cursor: pointer;width: 100px;border: none" data-uid="<?= $id; ?>" id="<?= $gift['id']; ?>" data-toggle="modal"
                                                                            data-target="#giftModal">
                            <img src="<?php echo $gift['icon']; ?>" alt="..." class="img-responsive" >
                                    <p style="text-align: center;"><?php  echo $gift['coin']?> <?= Yii::t('app','Coins')?></p>
                            </a>
                            </div>
                        <?php
                            }
                        }
                        ?>


                    </div>
                </div>
                <?php
                $cat_i++;
            }
            ?>
         </div>

    </div>
    <?php } ?>
    <div class="container">
        <h3><?php echo Yii::t('app','Received gifts'); ?></h3>

            <?php if(!$isOwnProfile) : ?>
             <a   href="#sendgift" style="color: #FFF;text-shadow: 0px 1px 0px rgba(0, 0, 0, 0.3);background: transparent linear-gradient(to bottom, #F2672A 0%, #EE3823 100%) repeat scroll 0% 0%;"
                 class="btn btn-default btn-sm">
                <?= Yii::t('app', 'Send gift') ?>
            </a>
            <?php endif; ?>
         <?php
         if(count($userGifts) > 0):
            foreach($userGifts  as $userGift)
            {
        ?>
                <div class="row" style="border-bottom: 1px solid #ccc">
                    <div class="col-lg-1">
                        <img src="<?php echo $userGift['icon']?>" height="72" width="72">
                    </div>
                    <div class="col-lg-5">
                        <h4><p><?php echo $userGift['comment']; ?></p></h4>

                        <p>
                            <?php if($isOwnProfile): ?>

                                <span style="float: right"><a  href="/profile/delete-gift?id=<?= $userGift['id']?>" onclick="return confirm('<?= Yii::t('yii','Are you sure you want to delete this item?')?>')"><span class="glyphicon glyphicon glyphicon-trash"></span> <?= Yii::t('app','Delete')?></a></span>

                            <?php endif; ?>

                            <a href="<?php echo Url::to('/u/'.$userGift['user_id'])?>">
                                <?php
                                    if($userGift['path']==''){
                                      $userGift['path'] =  $userGift['sex']==User::SEX_WOMAN?'/images/icons/female_0.png':'/images/icons/male_0.png';
                                     }
                                ?>

                                <img src="<?php echo $userGift['path']; ?>" width="32" height="32" class="img-circle"> <?php echo $userGift['full_name']; ?>
                            </a> <span style="padding-left: 10px">  <?php echo date('d/m/Y H:i',$userGift['time'])?></span>
                        </p>
                    </div>
                </div>
        <?php
            }
         else:
            echo Yii::t('app','This user has not a gift.').' '.Yii::t('app','Be the first sender.');

        endif;
        ?>

    </div>


</div>

