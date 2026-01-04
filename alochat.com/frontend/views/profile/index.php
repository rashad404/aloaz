<?php
/* @var $this yii\web\View */
use yii\helpers\Url;
use frontend\assets\PhotoUploadAsset;
use common\models\User;
use kartik\editable\Editable;
use yii\helpers\Json;


PhotoUploadAsset::register($this);

if ($isOwnProfile) {

    $this->registerJsFile(Yii::$app->request->baseUrl . '/js/fileinput_locale_' . Yii::$app->language . '.js', ['depends' => [PhotoUploadAsset::className()]]);
    $this->registerJsFile(Yii::$app->request->baseUrl . '/js/image_upload_init.js', ['depends' => [PhotoUploadAsset::className()]]);
}
$this->title = $user->nickname;
?>

<div class="profile-page-container">
    <?php if ($isOwnProfile): ?>
        <?= $this->render('partials/photo_upload_modal.php', ['imageForm' => $imageForm]); ?>
    <?php endif ?>

    <div class="row">

        <div style="margin: 0px 5px 0px 10px;min-height: 90px;">
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

                <span class="profile-name"><?= $user->nickname ?></span>
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
                        <a class="btn btn-primary btn-xs" href="<?= Url::to(['profile/settings']) ?>"> <?= Yii::t('app', 'Edit') ?></a>
                    </p>

                <?php endif ?>
                <?php if (!$isOwnProfile): ?>
                    <br />

                    <button id="like-user" onclick="likeUser(<?= $user->id ?>);" type="button"
                            class="btn btn-primary btn-xs">
                        <i class="glyphicon glyphicon-heart
                        <?= $user->userLiked() ? 'text-danger' : '' ?>

                        "></i> <?= Yii::t('app', 'Like') ?>
                    </button>

                    <a href="<?= Url::to(['/messages/view', 'u' => $user->id]) ?>#chat" type="button"
                       class="btn btn-primary btn-xs">
                        <i class="glyphicon glyphicon-envelope"></i> <?= Yii::t('app', 'Write') ?>
                    </a>
                    <button id="block-user" onclick="blockUser1(<?= $user->id ?>,'<?= $user->userBlocked() ? Yii::t('app','Are you sure you want to cancel block this user?') : Yii::t('app','Are you sure you want to block this user?'); ?>');" type="button"
                            class="btn btn-primary btn-xs">
                        <i class="glyphicon glyphicon-ban-circle
                        <?= $user->userBlocked() ? 'text-danger' : '' ?>

                        "></i> <?= Yii::t('app', 'Add block') ?>
                    </button>


                            <?php
                            $friendStatus = User::friendStatus($user->id);
                            if($friendStatus == 1){
                                // echo "sorgu gondermisiz ";
                                ?>
                                <button id="reset-friend" onclick="resetFriend(<?= $user['id']; ?>,'<?=   Yii::t('app','Cancel request for friendship');   ?>?');" type="button"
                                        class="btn btn-danger btn-xs">
                                    <?= Yii::t('app', 'Cancel request for friendship') ?>
                                </button>
                            <?php
                            } elseif($friendStatus == 2){
                                //  echo "sorgunuz gelib  tesdiq gozleyir";
                                ?>
                                <button id="reset-friend" onclick="resetFriend(<?= $user['id']; ?>,'<?=   Yii::t('app','Cancel?');   ?>?');" type="button"
                                        class="btn btn-danger btn-xs">
                                    <?= Yii::t('app', 'Cancel') ?>
                                </button>
                                <button id="confirm-friend" onclick="confirmFriend(<?= $user['id']; ?>,'<?=   Yii::t('app','Accept');   ?>?');" type="button"
                                        class="btn btn-success btn-xs">
                                    <?= Yii::t('app', 'Accept') ?>
                                </button>
                            <?php

                            } elseif($friendStatus == 3) {
                                //  echo "dostsunuz";
                                ?>
                                <button id="reset-friend" onclick="resetFriend(<?= $user['id']; ?>,'<?=   Yii::t('app','Are you sure to remove this user from your friendlist?');   ?>');" type="button"
                                        class="btn btn-danger btn-xs">
                                    <?= Yii::t('app', 'Cancel friendship') ?>
                                </button>
                            <?php
                            } else {
                                ?>
                                <button id="add-friend" onclick="addFriend(<?= $user->id ?>,'<?=   Yii::t('app','Are you sure to send friend request?'); ?>');" type="button"
                                        class="btn btn-primary btn-xs">
                                    <i class="glyphicon glyphicon-plus-sign
                        <?= $user->userIsFriend() ? 'text-danger' : '' ?>

                        "></i> <?= Yii::t('app', 'Add friend') ?>
                                </button>
                                <?php
                            }
                            ?>

                <?php endif ?>


            </div>

            <div class="clearfix"></div>

        </div>

    </div>
    <div class="arrow_box">
     <?php
          if ($isOwnProfile): ?>

            <?php
            echo Editable::widget([
                'model' => $user,
                'name'=>'last_post',
                'asPopover' => false,
                'value' => $user->last_post,
                'header' => 'Name',
                'size'=>'md',
                'options' => ['class'=>'form-control','rows'=> 2,'cols'=>50, 'placeholder'=>'Enter status...'],
                'formOptions'=>[
                    'action'=>Url::to(['status-update'])
                ],
                'inputType' => Editable::INPUT_TEXT,
                'inlineSettings' => [
                    'templateAfter' => Editable::INLINE_AFTER_1
                ],
                'editableButtonOptions' => [
                    'label' => Yii::t('app','Edit')
                ],
                'resetButton' => [
                    'icon' => Yii::t('app','Reset'),
                    'class' => 'btn btn-sm btn-danger'
                ],
                'submitButton' => [
                    'icon' => Yii::t('app','Edit'),
                    'class' => 'btn btn-sm btn-success'
                 ],
               // 'valueIfNull' => 'ddl'
            ]);

            ?>
        <?php
        else:
           if($user->last_post!=''){
                echo $user->last_post;
            } else {
                echo 'Status '.Yii::t('app', "(not set)");

            }
         endif ?>
    </div>

    <div class="row">
        <div class="container profile-info-container">
            <div class="row">
                <table class="table table-striped table-hover table-responsive table-bordered" style="margin-left: 7px;">

                    <tr>
                        <td class="col-md-4 col-xs-6 col-lg-4 col-sm-6"><?= Yii::t('app','Sex'); ?></td>
                        <td class="col-md-8 col-xs-6 col-lg-8 col-sm-6"><?php echo $user->getSexValue($user->sex); ?></td>
                    </tr>
                    <tr>
                        <td class="col-md-4 col-xs-6 col-lg-4 col-sm-6"><?= Yii::t('app','Location')?></td>
                        <td class="col-md-8 col-xs-6 col-lg-8 col-sm-6"><?php echo \common\models\Country::getCountryName($user->country_id);?>,<?php echo \common\models\City::getCityName($user->city_id);?></td>
                    </tr>
                    <tr>
                        <td class="col-md-4 col-xs-6 col-lg-4 col-sm-6"><?= Yii::t('app','Like count')?></td>
                        <td class="col-md-8 col-xs-6 col-lg-8 col-sm-6"><?= $user->getLikeCountUsers($user->id);?> </td>
                    </tr>
                    <tr>
                        <td class="col-md-4 col-xs-6 col-lg-4 col-sm-6"><?= Yii::t('app','Last login date')?></td>
                        <td class="col-md-8 col-xs-6 col-lg-8 col-sm-6">
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
                     </tr>
                    <tr>
                        <td class="col-md-4 col-xs-6 col-lg-4 col-sm-6"><?= Yii::t('app','Register date')?></td>
                        <td class="col-md-8 col-xs-6 col-lg-8 col-sm-6"><?= date("d-m-Y",$user->created_at);?></td>
                    </tr>
                    <tr>
                        <td class="col-md-4 col-xs-6 col-lg-4 col-sm-6"><?= Yii::t('app','Register Number (ID)'); ?></td>
                        <td class="col-md-8 col-xs-6 col-lg-8 col-sm-6"><?php echo $user->id; ?></td>
                    </tr>
                    <tr>
                        <td class="col-md-4 col-xs-6 col-lg-4 col-sm-6"><?= Yii::t('app','About me')?></td>
                        <td class="col-md-8 col-xs-6 col-lg-8 col-sm-6"><?= $user->about;?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="row">

            <!-- Container with last photos -->
            <div class="container photo-gallery-container">
                <p>
                    <b><?= Yii::t('app', 'Photos') ?>: </b>
                    <?php if (!$isOwnProfile && !$photoUploadAskExist): ?>
                        <button id="ask-upload-image" onclick="askUploadPhoto(<?= $user->id ?>);" type="button"
                                class="btn btn-primary btn-sm">
                            <?= Yii::t('app', 'Ask to upload') ?>
                        </button>
                    <?php endif ?>
                    <?php if ($isOwnProfile): ?>

                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-default btn-md" data-toggle="modal" data-target="#photoUploadModal">
                            <?= Yii::t('app', 'Add new photos') ?>
                        </button>

                    <?php endif ?>

                </p>
                <?php if ($userImages): ?>
                    <?php foreach ($userImages as $uImage): ?>

                            <div class="photo">
                            <img  src="<?= Url::base() . $uImage['path'] ?>"
                                 data-sec-id="<?= $user->id ?>"
                                 id="<?= $uImage['id'] ?>"/> <br />
                                <?php if ($isOwnProfile): ?>

                                    <p><a class="link1"  href="/profile/set-profile-picture?id=<?= $uImage['id']?>"><span class="glyphicon glyphicon glyphicon-user"></span><?= Yii::t('app','Set as profile picture')?></a>
                                       <br /> <a  href="/profile/delete-image?id=<?= $uImage['id']?>"><span class="glyphicon glyphicon glyphicon-trash"></span><?= Yii::t('app','Delete')?></a>
                                    </p>
                                <?php endif ?>

                            </div>

                    <?php endforeach ?>
                <?php endif ?>
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

    </div>


        <div style="margin-top: 5px; background: #E5F3DA none repeat scroll 0% 0%; margin-left: 0px;" class="row">
            <div class="container photo-gallery-container" style="margin-bottom: 0px;">
                <p>
                    <b><a href="<?php echo  Url::to('/gift/'.$user->id.'#usergifts')?>"><?= Yii::t('app', 'Gifts') ?>:</a> </b>
                    <?php if (!$isOwnProfile): ?>
                        <a   href="<?php echo Url::to(['/gift/'.$user->id.'#sendgift'])?>" style="float:right;color: #FFF;text-shadow: 0px 1px 0px rgba(0, 0, 0, 0.3);background: transparent linear-gradient(to bottom, #F2672A 0%, #EE3823 100%) repeat scroll 0% 0%;"
                                class="btn btn-default btn-sm">
                             <?= Yii::t('app', 'Send gift') ?>
                        </a>
                    <?php endif ?>

                </p>
                <?php
                if(count($userGifts)>0){
                    foreach($userGifts as $userGift){ ?>
                        <div class="col-xs-6 col-md-2 col=lg-2 col-sm-3">
                            <a  class="thumbnail giftId" href="<?php echo  Url::to('/gift/'.$user->id.'#usergifts')?>" style="width: 80px;border: none;background-color: transparent;cursor: pointer">
                                <img src="<?php echo  $userGift['icon']; ?>" alt="..." class="img-responsive" >
                            </a>
                        </div>
               <?php }
                }else {
                    echo '<div class="col-md-12" style="margin-bottom: 10px">'.Yii::t('app','This user has not a gift.').' '.Yii::t('app','Be the first sender.').'</div>';
                } ?>
            </div>
        </div>
 </div>

