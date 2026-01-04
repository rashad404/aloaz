<?php
/* @var $this yii\web\View */
use yii\helpers\Url;
use frontend\assets\PhotoUploadAsset;
use common\models\User;

PhotoUploadAsset::register($this);

if ($isOwnProfile) {

    $this->registerJsFile(Yii::$app->request->baseUrl . '/js/fileinput_locale_' . Yii::$app->language . '.js', ['depends' => [PhotoUploadAsset::className()]]);
    $this->registerJsFile(Yii::$app->request->baseUrl . '/js/image_upload_init.js', ['depends' => [PhotoUploadAsset::className()]]);
}
$this->title = $user->full_name;
?>
<div class="profile-page-container">
    <?php if ($isOwnProfile): ?>
        <?= $this->render('partials/photo_upload_modal.php', ['imageForm' => $imageForm]); ?>
    <?php
        else:
        echo $this->render('partials/gift_modal.php');
    endif ?>

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

                <span class="profile-name"><?= $user->full_name ?></span>
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
                        <p>
                            <div class="clearfix"></div>
                            <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#giftModal"> <?= Yii::t('app', 'Hediyye et') ?></a>
                        </p>

                    <?php endif ?>
                <?php if (!$isOwnProfile): ?>

                    <hr/>

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


            <br/>

        </div>
    </div>

    <br/>

    <div class="row">
        <div class="container profile-info-container">
            <div class="row">
                <table class="table table-striped table-hover table-responsive table-bordered" style="margin-left: 7px;">
                    <tr>
                        <td class="col-md-4 col-xs-6 col-lg-4 col-sm-6"><?= Yii::t('app','ID'); ?></td>
                        <td class="col-md-8 col-xs-6 col-lg-8 col-sm-6"><?php echo $user->id; ?></td>
                    </tr>
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
    <?php if ($isOwnProfile): ?>

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#photoUploadModal">
            <?= Yii::t('app', 'Add new photos') ?>
        </button>

    <?php endif ?>

</div>