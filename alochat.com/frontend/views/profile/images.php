<?php
use frontend\assets\PhotoUploadAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use common\models\User;
use kartik\editable\Editable;

PhotoUploadAsset::register($this);
$this->title =  $user->nickname ;
?>
<?php if ($isOwnProfile): ?>
    <?php
    $this->registerJsFile(Yii::$app->request->baseUrl . '/js/fileinput_locale_' . Yii::$app->language . '.js', ['depends' => [PhotoUploadAsset::className()]]);
    $this->registerJsFile(Yii::$app->request->baseUrl . '/js/image_upload_init.js', ['depends' => [PhotoUploadAsset::className()]]);
    ?>
    <?= $this->render('partials/photo_upload_modal.php', ['imageForm' => $imageForm]); ?>
<?php endif ?>
<div class="center-block hidden-xs">
    <div class="center-block-status">
        <div class="row center-block-status-content">
            <div class="col-md-11 col-sm-11 col-lg-11 center-block-status-user">
                <?= $user->nickname;?>
            </div>
            <div class="col-md-1 col-sm-1 col-lg-1">
                <?php if($user->isOnline()):?>
                    <span class="online">
                         <div class="status online"></div>
                         </span>
                <?php
                else:?>
                    <span class="online">
                             <div class="status-offline online"></div>
                         </span>
                <?php endif; ?>
            </div>
            <div class="col-md-12 center-block-status-text">
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
                        echo Yii::t('app', "(not set)");

                    }
                endif ?>
            </div>

        </div>

    </div>
</div>
<?php if (!$isOwnProfile): ?>

    <div class="col-md-12 hidden-xs user-buttons center-block">
        <a class="btn like-btn" id="like-user" onclick="likeUser(<?= $user->id ?>);">
            <div class="profile-like-ic"></div>
            <div class="like-btn-text liked" style="display: <?= $user->userLiked() ? 'block' : 'none' ?>">Liked</div>
            <div class="like-btn-text like" style="display: <?= $user->userLiked() ? 'none' : 'block' ?>">Like</div>
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
                <li>
                    <a class="cursor" id="report-user" onclick="reportUser(<?= $user->id ?>,'<?= $user->userReported() ? Yii::t('app','Are you sure you want to cancel report this user?') : Yii::t('app','Are you sure you want to report this user?'); ?>');">
                        <img src="/images/icons/sprite/spam.png">
                        <?= $user->userReported() ? 'Şikayəti götür' : Yii::t('app','Şikayət et') ?>
                    </a>
                </li>
                <li><a href="#"><img src="/images/icons/sprite/spam.png"> Spam</a></li>
            </ul>
        </div>

    </div>

<?php else : ?>

    <div class="col-md-12 hidden-xs user-buttons center-block">
        <a href="<?= Url::to(['/profile/settings']) ?>" class="btn like-btn" id="like-user">
            <div class="like-btn-text liked"><?= Yii::t('app','Profile settings')?></div>
        </a>
        <a  class="btn message-btn" data-toggle="modal" data-target="#photoUploadModal" >
            <div class="message-btn-text"><?= Yii::t('app','Add new photos')?></div>
        </a>
    </div>
<?php endif; ?>

<div class="clearfix"></div>


<div>
    <?= $this->render('/site/partials/image_gallery.php', ['images' => $images]); ?>
    <?php
    if(count($shares)>0){
        echo '<div class="row text-center"><a href="'.Url::to(["profile/timeline/".$user->id]).'" class="btn  btn-default">Bütün paylaşımlar</a></div><br />';
    }
    ?>

</div>