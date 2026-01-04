<?php
use frontend\assets\PhotoUploadAsset;
use yii\bootstrap\ActiveForm;

PhotoUploadAsset::register($this);
?>
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
                <?php endif; ?>
            </div>
            <div class="col-md-12 center-block-status-text">
                <?= $user->last_post?>
            </div>

        </div>

    </div>
</div>

<div class="clearfix"></div>


     <div class="center-block col-md-12">
        <div class="col-md-12 share-block">
            <div class="share-block-content">
                <img src="<?= $share["profile_photo"];?>" class="img-circle pull-left" height="50" width="50">
                <span class="share-block-username"><?php echo $share["nickname"]; ?></span>
                                    <span class="share-block-date"><?php echo date("d/m/Y H:i",$share["time"])?>
                                        <span class="online">
                                             <div class="status-offline online"></div>
                                        </span>
                                    </span>
                <div class="clearfix"></div>
                <div class="share-block-text">
                    <?php echo $share["text"]; ?>
                    <?php if($share['attach']!=""):?>
                        <img src="/images/share/thumbs/<?php echo $share["attach"]?>" class="img-responsive img-share">
                    <?php endif;?>
                </div>
            </div>
            <div class="share-block-icons">
                <img src="/images/icons/share/smile.png">
                <img src="/images/icons/share/photo.png">
                <img src="/images/icons/share/like.png">
                <img src="/images/icons/share/comment.png">

                <div class="clearfix"></div>
            </div>
        </div>
    </div>
