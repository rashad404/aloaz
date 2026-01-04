<?php
use frontend\assets\SimpleAsset;
use yii\widgets\ActiveForm;
use \yii\helpers\Url;
use \yii\helpers\Html;
use \common\models\Share;


 SimpleAsset::register($this);

$this->title = $user["nickname"];
$text = strip_tags($share["text"]);
$this->title.= $text!=""?" ".mb_substr($text,0,160,'UTF-8'):' AloChat.com - Azərbaycanın Sosial Şəbəkəsi. Burada yaxınlarınla ünsiyyət qura, yeni insanlarla tanış ola, şəkil və video paylaşa bilərsən!';


$keys = Share::getShareKeywords($text);
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
    <div class="col-md-9 col-lg-9 col-xs-12 col-sm-9">
        <div class="row">
            <?= \frontend\components\LoginProfileLeftBarWidget::widget(['actionName' => Yii::$app->controller->action->id]);?>

        </div>
        <div class="row" style="margin-left: 5px; margin-right: 5px;">
            <div class="row">


                <div class="center-block col-md-12" style="margin-bottom: 0px;">
                    <div class="col-md-12 share-block">
                        <div class="share-block-content">
                            <img src="<?= $user["profile_photo"];?>" class="img-circle pull-left" height="50" width="50">
                            <span class="share-block-username"><?php echo $user["nickname"]; ?></span>
                            <span class="share-block-date"><?php echo Share::getDate($share["time"])?>
                                <span class="online">
                                     <div class="status-offline online"></div>
                                </span>
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


                    </div>
                </div>
                <div class="send-share-block col-md-12" style="border-top: 0px !important;">

                    <div class="icons">
                        <div class="share-block-icons">
                            <a class="cursor"  id="like-share">
                                <img id="share-img-<?php echo $share["id"]; ?>" src="<?php if(\common\models\Share::liked(Yii::$app->user->id,$share["id"])) echo '/images/icons/share/liked.png'; else echo '/images/icons/share/like.png'; ?>">
                                <span id="like_count_<?= $share["id"]?>"><?= $share["like_count"]>0?$share["like_count"]:'';?></span>
                            </a>

                            <a href="<?= \yii\helpers\Url::to(['/site/post/'.$share["id"].'#post'])?>">
                                <img  src="/images/icons/share/read.png">
                                <?= $share["read_count"]>0?$share["read_count"]:'';?>

                            </a>

                            <a href="<?= \yii\helpers\Url::to(['/site/post/'.$share["id"].'#post'])?>">
                                <img  src="/images/icons/share/comment.png">
                                <?= $share["comment_count"]>0?$share["comment_count"]:'';?>

                            </a>

                            <div class="clearfix"></div>
                        </div>
                     </div>

                 </div>

                <div class="center-block" style="padding: 15px;">
                    <?php foreach($comments as $comment):?>
                        <div style="padding-top: 10px;border-bottom: 1px solid #e3e3e3;padding-bottom: 10px;" class="">
                            <img src="<?= $comment["profile_photo"]; ?>" class="img-circle pull-left" height="30" width="30">
                            <span style="padding: 5px; margin-left: 0px;" class="share-block-username"><?php echo $comment["nickname"]; ?></span>
                        <span class="share-block-date"><?= date("d/m/Y H:i",$comment["time"])?>
                            <span class="online">
                                 <div class="status-offline online"></div>
                            </span>
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
            </div>
        </div>
    </div>
    <div class="col-md-3 col-lg-3 hidden-xs hidden-sm profile-right">

        <div class="right-block">
            <div class="login-panel">
                <div class="login-panel-title">
                    <div class="login-title">Login</div>
                    <div class="register-title">
                        <div class="register-title-btn"><a href="<?= Url::to(["/site/register"])?>">Registration</a></div>
                    </div>
                    <div class="clearfix"></div>

                </div>
                <div class="login-panel-body">
                    <?php $form = ActiveForm::begin(['id' => 'form-sigin']); ?>
                    <label><span class="login-panel-label">Username, email or phone number</span></label>
                    <?= $form->field($loginForm, 'email')
                        ->textInput(['placeholder' => Yii::t('app', 'Email')])->label(false) ?>
                    <label> <span class="login-panel-label">Password</span></label>
                    <?= $form->field($loginForm, 'password')
                        ->passwordInput(['placeholder' => Yii::t('app', 'Password')])
                        ->label(false)
                    ?>
                    <div class="form-group" style="margin-bottom: 16px;">
                        <span style="float: left"><a href="<?= Url::to(['site/request-password-reset'])?>"   style="color:#CCC;padding: 5px;float: left"><?= Yii::t('app','Forgot your password?');?></a></span>
                        <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn login-btn pull-right', 'name' => 'signup-button']) ?>
                        <div class="clearfix"></div>
                    </div>
                    <?php ActiveForm::end(); ?>
                    <p class="or-signin hidden-lg  hidden-md"><?= Yii::t('app', 'or sign in with') ?></p>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xs-6 col-sm-6 facebook-block">
                                <a href="<?= Url::to(['/site/auth?authclient=facebook'])?>">
                                    <div class="container-fluid facebook-block-full">
                                        <div class="facebook-block-left"><img src="/images/icons/f.png"></div>
                                        <div class="facebook-block-right"><span class="hidden-xs hidden-sm">Sign up with </span>Facebook</div>
                                        <div class="clear"></div>
                                    </div>
                                </a>

                            </div>
                            <div class="col-md-12 col-lg-12 col-xs-6 col-sm-6 twitter-block">
                                <a href="<?= Url::to(['/site/auth?authclient=google'])?>">
                                    <div class="container-fluid twitter-block-full">
                                        <div class="twitter-block-left"">
                                        <img src="/images/icons/g.png">
                                    </div>
                                    <div class="twitter-block-right">
                                        <span class="hidden-xs hidden-sm">Sign up with </span>Google
                                    </div>
                                    <div class="clear"></div>
                            </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div>


        <div class="right-block">
            <?= \frontend\components\LastShareBarWidget::widget(); ?>
        </div>


</div>
</div>


