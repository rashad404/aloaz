<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\modal;
?>


<?php
$href = 'profile';
if(Yii::$app->user->isGuest){
    $href = 'site';
}
$smilesArray = \common\models\ConversationReply::getEmojis();
    if(1){ ?>
        <h4 class="text-center" style="color: #00b2c0">Top paylaşımlar</h4>
        <ul class="nav nav-tabs popular-posts-nav" role="tablist">
            <li role="presentation" class="active"><a href="#day" aria-controls="day" role="tab" data-toggle="tab">24 saat</a></li>
            <li role="presentation"><a href="#week" aria-controls="week" role="tab" data-toggle="tab">1 həftə</a></li>
        </ul>
        <div class="tab-content scroller" id="scroller">
            <div role="tabpanel" class="tab-pane top-tab-pane active" id="day">
                            <?php
                            foreach($shares as $share):?>
                            <a href="<?= Url::to(['/'.$href.'/post/'.$share["id"]])?>">
                                <div class="right-block-post">
                                    <div class="right-post-block-user">
                                        <img src="<?= $share["profile_photo"]?>" class="img-circle" height="33" width="33">
                                    </div>
                                    <div class="right-block-post-text">
                                        <?php if($share["text"]!=""):
                                            $share["text"] = strip_tags(mb_substr($share["text"],0,160,'UTF-8'));
                                            $share["text"] = str_replace(array_keys($smilesArray), array_values($smilesArray),   $share["text"]);

                                            ?>
                                            <div class="right-block-post-text2"><?= $share["text"]; ?></div>
                                        <?php  endif; ?>
                                        <?php if($share["attach"]!=""):
                                            $date_folder = date('Ym',$share["time"]);
                                            ?>

                                            <img src="/images/share/resized/<?= $date_folder?>/<?= $share["attach"]?>" class="img-responsive img-share top-image-share">
                                        <?php endif; ?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <p class="pull-left top-share-icons" style="text-align: left;margin-left: 60px;color: ">
                                        <a class="cursor"  id="like-share" onclick="likeShare(<?= $share["id"] ?>);">
                                            <img style="width: 15px;" id="share-img-<?php echo $share["id"]; ?>" src="<?php if(\common\models\Share::liked(Yii::$app->user->id,$share["id"])) echo '/images/icons/share/liked.png'; else echo '/images/icons/share/like.png'; ?>">
                                            <span id="like_count_<?= $share["id"]?>"><?= $share["like_count"]>0?$share["like_count"]:'';?></span>
                                        </a>

                                        <a href="<?= \yii\helpers\Url::to(['/profile/post/'.$share["id"].'#post'])?>" >
                                            <img  src="/images/icons/share/read.png" style="width:15px;">
                                            <?= $share["read_count"]>0?$share["read_count"]:'';?>

                                        </a>
                                    </p>
                                    <p class="pull-right" style="text-align: right;font-size: 11px;margin-right: 7px;color: #7b888f;"><?php echo \common\models\Share::getDate($share["time"])?></p>
                                    <div class="clearfix"></div>

                                </div>
                            </a>

                            <?php endforeach; ?>

            </div>
            <div role="tabpanel" class="tab-pane top-tab-pane" id="week">
                <?php
                foreach($weekShares as $share):?>
                    <a href="<?= Url::to(['/'.$href.'/post/'.$share["id"]])?>">
                        <div class="right-block-post">
                            <div class="right-post-block-user">
                                <img src="<?= $share["profile_photo"]?>" class="img-circle" height="33" width="33">
                            </div>
                            <div class="right-block-post-text">
                                <?php if($share["text"]!=""):
                                    $share["text"] = mb_substr($share["text"],0,160,'UTF-8');
                                    $share["text"] = str_replace(array_keys($smilesArray), array_values($smilesArray),   $share["text"]);

                                    ?>
                                    <div class="right-block-post-text2"><?= $share["text"]; ?></div>
                                <?php  endif; ?>
                                <?php if($share["attach"]!=""):
                                    $date_folder = date('Ym',$share["time"]);
                                    ?>

                                    <img src="/images/share/resized/<?= $date_folder?>/<?= $share["attach"]?>" class="img-responsive img-share top-image-share">
                                <?php endif; ?>
                            </div>
                            <div class="clearfix"></div>
                            <p class="pull-left top-share-icons" style="text-align: left;margin-left: 60px;">
                                <a class="cursor"  id="like-share" onclick="likeShare(<?= $share["id"] ?>);">
                                    <img style="width: 15px;" id="share-img-<?php echo $share["id"]; ?>" src="<?php if(\common\models\Share::liked(Yii::$app->user->id,$share["id"])) echo '/images/icons/share/liked.png'; else echo '/images/icons/share/like.png'; ?>">
                                    <span id="like_count_<?= $share["id"]?>"><?= $share["like_count"]>0?$share["like_count"]:'';?></span>
                                </a>

                                <a href="<?= \yii\helpers\Url::to(['/profile/post/'.$share["id"].'#post'])?>">
                                    <img  src="/images/icons/share/read.png" style="width:15px;">
                                    <?= $share["read_count"]>0?$share["read_count"]:'';?>

                                </a>
                            </p>
                            <p class="pull-right" style="text-align: right;font-size: 11px;margin-right: 7px;color: #7b888f;"><?php echo \common\models\Share::getDate($share["time"])?></p>
                            <div class="clearfix"></div>

                        </div>
                    </a>

                <?php endforeach; ?>
            </div>
        </div>
<?php  }  ?>
<?php

  /*  if($_SERVER["REMOTE_ADDR"] == '37.32.67.22'){
        echo $ferqTime;
    }*/

/*if($_SERVER["REMOTE_ADDR"] == '37.32.67.22'){
    foreach($shares as $share):*/?><!--
    <a href="<?/*= Url::to(['/'.$href.'/post/'.$share["id"]])*/?>">
       <div class="right-block-post">
            <div class="right-post-block-user">
                <img src="<?/*= $share["profile_photo"]*/?>" class="img-circle" height="33" width="33">
            </div>
            <div class="right-block-post-text">
            <?php /*if($share["text"]!=""):
                $share["text"] = mb_substr($share["text"],0,160,'UTF-8');
                $share["text"] = str_replace(array_keys($smilesArray), array_values($smilesArray),   $share["text"]);

                */?>
                 <div class="right-block-post-text2"><?/*= $share["text"]; */?></div>
            <?php /* endif; */?>
                <?php /*if($share["attach"]!=""):
                    $date_folder = date('Ym',$share["time"]);
                    */?>

                     <img src="/images/share/resized/<?/*= $date_folder*/?>/<?/*= $share["attach"]*/?>" class="img-responsive img-share">
                <?php /*endif; */?>
            </div>

           <p class="pull-right" style="text-align: right;font-size: 11px;margin-right: 15px;color: #7b888f;width: 100%"><?php /*echo \common\models\Share::getDate($share["time"])*/?></p>
           <div class="clearfix"></div>

       </div>
    </a>

--><?php /*endforeach; }*/?>