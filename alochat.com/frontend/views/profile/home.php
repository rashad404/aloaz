<?php
use frontend\assets\PhotoUploadAsset;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use common\models\User;
use kartik\editable\Editable;

PhotoUploadAsset::register($this);
$this->title =  $user->nickname ;
?>
<?php
/*if($_SERVER["REMOTE_ADDR"]=='37.32.67.22'){
    echo "Opened: " .$ferqTime;
}*/
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
            <li>
                <a class="cursor" id="report-user" onclick="reportUser(<?= $user->id ?>,'<?= $user->userReported() ? Yii::t('app','Are you sure you want to cancel report this user?') : Yii::t('app','Are you sure you want to report this user?'); ?>');">
                    <img src="/images/icons/sprite/spam.png">
                    <?= $user->userReported() ? 'Şikayəti götür' : Yii::t('app','Şikayət et') ?>
                </a>
            </li>
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

<div class="hidden-xs center-block profile-user-details-block">
    <div class="row  margin-0">
        <div class="col-xs-12 border-block">
            <div class="row profile-user-details-row">

                <div class="col-xs-6 user-details-left" ><?= Yii::t('app','Name'); ?></div>
                <div class="col-xs-6 user-details-right"> 	<?php echo $user->full_name; ?></div>
            </div>
            <div class="row profile-user-details-row">

                <div class="col-xs-6 user-details-left" ><?= Yii::t('app','Sex'); ?></div>
                <div class="col-xs-6 user-details-right"> 	<?php echo $user->getSexValue($user->sex); ?></div>
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
<?php if($isOwnProfile):?>
<div class="send-share-block col-md-12">
    <?php $form = ActiveForm::begin(['id' => 'login-form',
        'options' => ['enctype'=>'multipart/form-data']
    ]); ?>
    <?= $form->field($model,'text')->textarea(['class' => 'share-textarea','id' => 'share-text'])->label(false);?>
    <?= $form->field($model,'attach')->fileInput(['style' => 'display:none'])->label(false); ?>
     <div class="share-form-icons">
        <div style="float:left">
         <img class="share-smile-icon cursor" id="control-smile" src="/images/icons/share/smile.png">
            <ul class="wink-actions1"  data-show="0" id="wink-box">
                <?php
                $smilesArray = \common\models\ConversationReply::getSmiles();
                foreach($smilesArray as $key=>$value){
                    ?>
                    <li class="do" style="float: left;
                                    width: 34px;
                                    height: 40px;">
                        <a onclick="addSmile(this);"
                           href="javascript:;"  rel="<?= $key; ?>">
                            <img class="smile" src="/images/smiles/<?= $value ?>.png" alt="<?= $key; ?>"/>            </a>
                    </li>
                <?php }
                ?>

            </ul>

            <img class="share-photo-icon cursor" id="share-upload-photo" src="/images/icons/share/photo.png">
         <span id="share_filename"></span>
         </div>
         <div style="float: right">
             <?= \yii\helpers\Html::submitButton(Yii::t('app','Share'),['class' => 'btn blue-btn pull-right'])?>
         </div>
         <div class="share-permission">
             <?//= $form->field($model,'permission')->dropDownList([0=>Yii::t('app','Everyone'),1=>Yii::t('app','Friends')],['class' => 'form-select'])->label(false); ?>
             <?= $form->field($model,'permission')->checkbox([1])->label(Yii::t('app','Only Friends')); ?>
         </div>

         <div class="clear"></div>

     </div>

    <?php ActiveForm::end(); ?>
</div>
<?php endif; ?>

<div>
<?= $this->render('/site/partials/share_gallery.php', ['shares' => $shares]); ?>
<?php
if(count($shares)>0){
    echo '<div class="row text-center"><a href="'.Url::to(["profile/timeline/".$user->id]).'" class="btn  btn-default">Bütün paylaşımlar</a></div><br />';
}
?>

</div>