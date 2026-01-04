<?php
use frontend\assets\PhotoUploadAsset;
use yii\bootstrap\ActiveForm;
use common\models\User;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;

PhotoUploadAsset::register($this);
$this->title = $userModel["nickname"];
?>


<?php if ($isOwnProfile): ?>
   <?php
    $this->registerJsFile(Yii::$app->request->baseUrl . '/js/fileinput_locale_' . Yii::$app->language . '.js', ['depends' => [PhotoUploadAsset::className()]]);
    $this->registerJsFile(Yii::$app->request->baseUrl . '/js/image_upload_init.js', ['depends' => [PhotoUploadAsset::className()]]);
    ?>
    <?= $this->render('partials/photo_upload_modal.php', ['imageForm' => $imageForm]); ?>
<?php endif ?>

<div class="row">

    <div class="col-md-12" style="background-color: #f5f5f5">
        <div class="row profile-title-block" id="user-filter1">
            <div class="col-md-12">
                <ul class="nav nav-tabs profile-nav-tabs">
                    <li role="presentation"><a href="<?php echo Url::to(['/profile/timeline/'.$userModel["id"]])?>">Timeline</a></li>
                    <li role="presentation" class="active"><a href="<?php echo Url::to(['/profile/photos/'.$userModel["id"]])?>"><?= Yii::t('app','Photos')?></a></li>
                    <li role="presentation"><a href="<?php echo Url::to(['/profile/friends/'.$userModel["id"]])?>"><?= Yii::t('app','Friends')?></a></li>
                    <li role="presentation"><a href="<?php echo  Url::to(['/profile/gifts/'.$userModel["id"]])?>"><?= Yii::t('app','Gifts')?></a></li>
                </ul>
            </div>
        </div>
    </div>
<div class="center-block col-md-12">

    <!--------------->
    <div class="row" >
        <!-- Container with last photos -->
        <div class="container photo-gallery-container">
            <p>
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
                              data-sec-id="<?= $userModel->id ?>"
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
    <!--------------->



</div>
</div>
 