<?php
use frontend\assets\PhotoUploadAsset;
use yii\bootstrap\ActiveForm;

PhotoUploadAsset::register($this);
$this->title = $user["nickname"];
$text = strip_tags($share["text"]);
$this->title.= $text!=""?" ".mb_substr($text,0,160,'UTF-8'):' AloChat.com - Azərbaycanın Sosial Şəbəkəsi. Burada yaxınlarınla ünsiyyət qura, yeni insanlarla tanış ola, şəkil və video paylaşa bilərsən!';


$keys = \common\models\Share::getShareKeywords($text);
$keywords = $keys!=""?" ".$keys:'Sosial şəbəkə, Chat, Tanışlıq, Mesaj, Əyləncə, Dost Tap, Paylaş, Azərbaycanda Tanışlıq';
$description = $text!=""?" ".mb_substr($text,0,200,'UTF-8'):'AloChat.com - Azərbaycanın Sosial Şəbəkəsi. Burada yaxınlarınla ünsiyyət qura, yeni insanlarla tanış ola, şəkil və video paylaşa bilərsən!';

$this->registerMetaTag(['name' => 'keywords', 'content' => $keywords]);

$this->registerMetaTag(['name' => 'description', 'content' => $description]);

$this->registerMetaTag(['property' => 'og:title', 'content' => htmlspecialchars_decode($this->title)]);

$this->registerMetaTag(['property' => 'og:type', 'content' => 'article']);

$this->registerMetaTag(['property' => 'og:url', 'content' => Yii::$app->request->getUrl()]);

$this->registerMetaTag(['property' => 'og:image', 'content' => 'http://alochat.com/images/alochat_logo.png']);

$this->registerMetaTag(['property' => 'og:site_name', 'content' => 'Alochat.com']);
  ?>


<div class="row">


         <div class="center-block col-md-12">
            <div class="col-md-12 share-block">
                <div class="share-block-content">
                    <img src="<?= $user->profile_photo;?>" class="img-circle pull-left" height="50" width="50">
                    <span class="share-block-username"><?php echo $user->nickname; ?></span>
                            <span class="share-block-date"><?php echo date("d/m/Y H:i",$share["time"])?>
                                <?php if(time() - $user->last_activity < Yii::$app->params['userOnlineStatusCheckTime']){?>
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
                    <div class="share-block-text">
                        <?php echo $share["text"]; ?>
                        <?php if($share['attach']!=""):
                            $date_folder = date("Ym",$share["time"]);
                            ?>
                            <img src="/images/share/thumbs/<?= $date_folder?>/<?php echo $share["attach"]?>" class="img-responsive img-share">
                        <?php endif;?>
                    </div>
                </div>
                <div class="share-block-icons">
                    <!--<img src="/images/icons/share/smile.png">
                    <img src="/images/icons/share/photo.png">-->
                    <a class="cursor"  id="like-share" onclick="likeShare(<?= $share["id"] ?>);">
                        <img id="share-img-<?php echo $share["id"]; ?>" src="<?php if(\common\models\Share::liked(Yii::$app->user->id,$share["id"])) echo '/images/icons/share/liked.png'; else echo '/images/icons/share/like.png'; ?>">
                        <span id="like_count_<?= $share["id"]?>"><?= $share["like_count"]>0?$share["like_count"]:''?></span>
                    </a>

                    <a>
                        <img  src="/images/icons/share/read.png">
                        <?= $share["read_count"]>0?$share["read_count"]:'';?>

                    </a>

                    <a>
                        <img  src="/images/icons/share/comment.png">
                        <?= $share["comment_count"]>0?$share["comment_count"]:'';?>

                    </a>
                    <?php
                    if($share["user_id"]==$user_id){
                        echo '<a class="pull-right" href="'.\yii\helpers\Url::to(['/profile/delete-share/'.$share["id"]]).'" style="color: #428BCA;">Sil</a>';
                    }
                    ?>

                    <div class="clearfix"></div>
                </div>

            </div>
         </div>
        <div class="send-share-block col-md-12" style="margin-top: -10px;border-top: 0px !important;">
            <?php $form = ActiveForm::begin(['id' => 'login-form',
                'options' => ['enctype'=>'multipart/form-data']
            ]); ?>
            <?= $form->field($comment,'comment')->textInput(['class' => 'share-textarea','style'=>'height:40px']);?>
            <div class="icons">

                <?= \yii\helpers\Html::submitButton(Yii::t('app','Send'),['class' => 'btn blue-btn pull-right'])?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    <?php if($comments): ?>
    <div class="center-block" style="padding: 15px;">
        <?php foreach($comments as $comment):?>
            <div style="padding-top: 10px;border-bottom: 1px solid #e3e3e3;padding-bottom: 10px;" class="">
                <a href="<?= \yii\helpers\Url::to(['/u/'.$comment["user_id"]])?>">
                    <img src="<?= $comment["profile_photo"]; ?>" class="img-circle pull-left" height="30" width="30">
                    <span style="padding: 5px; margin-left: 0px;" class="share-block-username"><?php echo $comment["nickname"]; ?></span>
                </a>
                        <span class="share-block-date"><?= \common\models\Share::getDate($comment["time"])?>
                            <?php if(time() - $comment["last_activity"] < Yii::$app->params['userOnlineStatusCheckTime']){?>
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
                <div style="line-height: normal;padding-left: 35px;" class="share-block-text">
                    <?php
                    //$commenttext = str_replace(':)','<img src="/images/icons/share/liked.png">',$comment['comment']);

                    ?>
                    <?= $comment['comment']; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif?>
</div>