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
    if ($shares): ?>

        <?php foreach($shares as $share):?>
            <div class="center-block col-md-12 share-block-outer">
                <div class="col-md-12 share-block">
                    <div class="share-block-content">
                        <a href="<?= Url::to(['profile/home/'.$share["user_id"]])?>">
                            <img src="<?= $share["profile_photo"];?>" class="img-circle pull-left" height="50" width="50">
                            <span class="share-block-username"><?php echo $share["nickname"];; ?></span>
                        </a>
                                    <span class="share-block-date"><?php echo Share::getDate($share["time"])?>
                                        <?php if(time() - $share["last_activity"] < Yii::$app->params['userOnlineStatusCheckTime']){?>
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
                        <a href="<?= Url::to(['/profile/post/'.$share["id"]])?>">
                            <div class="share-block-text">
                                <?php echo  Share::substrText($share["text"],500); ?>
                                <?php if($share['attach']!=""):?>
                                    <?php $date_folder = date("Ym",$share["time"])?>
                                    <img src="/images/share/thumbs/<?= $date_folder?>/<?php echo $share["attach"]?>" class="img-responsive img-share">
                                <?php endif;?>
                            </div>
                        </a>
                    </div>
                    <div class="share-block-icons">
                        <!--<img src="/images/icons/share/smile.png">
                        <img src="/images/icons/share/photo.png">-->
                        <a class="cursor"  id="like-share" onclick="likeShare(<?= $share["id"] ?>);">
                            <img id="share-img-<?php echo $share["id"]; ?>" src="<?php if(\common\models\Share::liked(Yii::$app->user->id,$share["id"])) echo '/images/icons/share/liked.png'; else echo '/images/icons/share/like.png'; ?>">
                            <span id="like_count_<?= $share["id"]?>"><?= $share["like_count"]>0?$share["like_count"]:'';?></span>
                        </a>

                        <a href="<?= \yii\helpers\Url::to(['/profile/post/'.$share["id"].'#post'])?>">
                            <img  src="/images/icons/share/read.png">
                            <?= $share["read_count"]>0?$share["read_count"]:'';?>

                        </a>

                        <a href="<?= \yii\helpers\Url::to(['/profile/post/'.$share["id"].'#post'])?>">
                            <img  src="/images/icons/share/comment.png">
                            <?= $share["comment_count"]>0?$share["comment_count"]:'';?>

                        </a>
                        <?php
                            if($share["user_id"]==$user_id){
                                echo '<a class="pull-right" href="'.Url::to(['/profile/delete-share/'.$share["id"]]).'" style="color: #428BCA;">Sil</a>';
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
                <p><?= Yii::t('app', 'Share not found.') ?></p>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>