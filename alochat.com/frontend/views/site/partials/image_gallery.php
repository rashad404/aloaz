<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 04.05.2015
 * Time: 18:07
 */
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\User;
use common\models\Share;

?>


<div class=""> <!--row-->

    <?php
    $i=1;$st= '';
    $user_id = Yii::$app->user->id;
    if ($images):     ?>

        <?php foreach($images as $image):?>
            <div class="center-block col-md-12 share-block-outer">
                <div class="col-md-12 share-block">
                    <div class="share-block-content">
                        <a href="<?= Url::to(['profile/home/'.$image["user_id"]])?>">
                            <img src="<?= $image["profile_photo"];?>" class="img-circle pull-left" height="50" width="50">
                            <span class="share-block-username"><?php echo $image["nickname"];; ?></span>
                        </a>
                                    <span class="share-block-date"><?php echo Share::getDate($image["time"])?>
                                        <?php if(time() - $image["last_activity"] < Yii::$app->params['userOnlineStatusCheckTime']){?>
                                            <span class="online">
                                                 <div class="status online"></div>
                                                 </span>
                                        <?php } else { ?>
                                            <span class="online">
                                             <div class="status-offline online"></div>
                                        </span>
                                        <?php } ?>
                                    </span>
                        <div class="clearfix"></div>
                        <a href="<?= Url::to(['/profile/image/'.$image["id"]])?>">
                            <div class="share-block-text">
                                <?php if($image['path']!=""):?>
                                    <?php $date_folder = date("Ym",$image["time"])?>
                                    <img src="<?php echo $image["path"]?>" class="img-responsive img-share">
                                <?php endif;?>
                            </div>
                        </a>
                    </div>
                    <div class="share-block-icons">
                        <!--<img src="/images/icons/share/smile.png">
                        <img src="/images/icons/share/photo.png">-->
                        <a class="cursor"  id="like-image" onclick="likeImage(<?= $image["id"] ?>);">
                            <img id="image-img-<?php echo $image["id"]; ?>" src="<?php if(\common\models\UserImage::liked(Yii::$app->user->id,$image["id"])) echo '/images/icons/share/liked.png'; else echo '/images/icons/share/like.png'; ?>">
                            <span id="like_count_<?= $image["id"]?>"><?= $image["like_count"]>0?$image["like_count"]:'';?></span>
                        </a>

                        <a href="<?= \yii\helpers\Url::to(['/profile/image/'.$image["id"].'#post'])?>">
                            <img  src="/images/icons/share/read.png">
                            <?= $image["read_count"]>0?$image["read_count"]:'';?>

                        </a>

                        <a href="<?= \yii\helpers\Url::to(['/profile/image/'.$image["id"].'#post'])?>">
                            <img  src="/images/icons/share/comment.png">
                            <?= $image["comment_count"]>0?$image["comment_count"]:'';?>

                        </a>
                        <?php
                            if($image["user_id"]==$user_id){
                                echo '<a class="pull-right" href="'.Url::to(['/profile/delete-image/'.$image["id"]]).'" style="color: #428BCA;">Sil</a>';
                            }
                        ?>




                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>            <?php else: ?>
    <div class="center-block col-md-12 share-block-outer">
        <div class="col-md-12 share-block">
            <div class="share-block-content">
                <p><?= Yii::t('app', 'Image not found.') ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>