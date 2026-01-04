<?php
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Share;

//$this->title = 'Alochat.com - Free dating,Online dating, dating singles, best online free dating site';

$this->title = 'AloChat.com - Azərbaycanın Sosial Şəbəkəsi. Burada yaxınlarınla ünsiyyət qura, yeni insanlarla tanış ola, şəkil və video paylaşa bilərsən!';

$keywords = 'Sosial şəbəkə, Chat, Tanışlıq, Mesaj, Əyləncə, Dost Tap, Paylaş, Azərbaycanda Tanışlıq';
$this->registerMetaTag(['name' => 'keywords', 'content' => $keywords]);

$this->registerMetaTag(['name' => 'description', 'content' => $this->title]);

$this->registerMetaTag(['property' => 'og:title', 'content' => htmlspecialchars_decode($this->title)]);

$this->registerMetaTag(['property' => 'og:type', 'content' => 'article']);

$this->registerMetaTag(['property' => 'og:url', 'content' => Yii::$app->request->getUrl()]);

$this->registerMetaTag(['property' => 'og:image', 'content' => 'http://alochat.com/images/alochat_logo.png']);

$this->registerMetaTag(['property' => 'og:site_name', 'content' => 'Alochat.com']);
?>
<?= $this->render('/site/partials/modals/login_alert_modal.php'); ?>
<div id="main" class="container-fluid">
    <div class="row main1">
        <div class="col-md-9  col-lg-9 hidden-xs hidden-sm">
            <div class="active-users-title">
                          <span style="background-color: #f5f5f5; position: relative; top: -0.5em;">
                           &nbsp;&nbsp; ALO CHATIN AKTİV İSTİFADƏÇİLƏRİ
                          </span>
            </div>

            <div class="row" style="margin-top:45px;">
                <?php foreach($users as $user):?>
                    <div class="col-md-15" style="height: 140px;">
                        <div style="float: right;width:10px;">
                            <span class="online"><div class="status online"></div></span>
                        </div>
                        <div class="ih-item circle colored effect19">
                            <a href="#">
                                <div class="img" style="float: left;">
                                    <img src="<?= Url::base()?><?= $user["profile_photo"]?>" alt="..." class="img-circle img-responsive">
                                </div>

                                <div class="clearfix"></div>
                                <div class="info">
                                    <p>Baki</p>
                                    <h3><?= $user["nickname"]?></h3>
                                    <p><?= $user["age"]?></p>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <div class="login-panel">
                <div class="login-panel-title">
                    <div class="login-title"><?= Yii::t('app','Login')?></div>
                    <div class="register-title">
                        <div class="register-title-btn"><a href="<?= Url::to([$country_code."/site/register"])?>"><?= Yii::t('app','Registration')?></a></div>
                    </div>
                    <div class="clearfix"></div>

                </div>
                <div class="login-panel-body">
                    <?php $form = ActiveForm::begin(['id' => 'form-sigin','enableClientValidation' => false,
                        'enableAjaxValidation' => false]); ?>
                    <label><span class="login-panel-label"><?= Yii::t('app','Username, email or phone number')?></span></label>
                    <?= $form->field($loginForm, 'email')
                        ->textInput(['placeholder' => Yii::t('app', 'Email')])->label(false) ?>
                    <label> <span class="login-panel-label"><?= Yii::t('app', 'Password')?></span></label>
                    <?= $form->field($loginForm, 'password')
                        ->passwordInput(['placeholder' => Yii::t('app', 'Password')])
                        ->label(false)
                    ?>
                    <div class="row login-btn-row">
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
                                    <div class="facebook-block-right"><span class="hidden-xs hidden-sm"><?= Yii::t('app', 'Sign up with Facebook')?> </span></div>
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
                                        <span class="hidden-xs hidden-sm"><?= Yii::t('app', 'Sign up with Google')?> </span>
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

    </div>
    <div class="row">
        <div class="col-sm-12 col-xs-12 hidden-lg hidden-md">
            <div class="users-block-title">
                Online: <?= $onlineCount?><br />
                <div class="row" style="margin-top: 3px;">
                    <?php $i=1;
                    foreach($users as $user):?>
                        <div class="col-sm-3  col-xs-4" style="margin-bottom: 8px;">
                            <a href="<?= Url::to(['/u/'.$user["id"]])?>">
                                <div class="">
                                    <img class="img-responsive img-rounded" data-placement="left" title="<?= $user["nickname"]?>" src="<?= $user["profile_photo"]?>">
                                </div>
                            </a>
                        </div>

                     <?php
                        if ($i++ > 5) break;
                        endforeach;
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-9 col-lg-9">
            <?php foreach($shares as $share):?>
                <div class="col-md-12 share-block">
                    <a href="<?= Url::to([$country_code."/site/post/".$share["id"]])?>" class="home-share-link">
                        <div class="share-block-content">
                            <img src="<?= Url::base()?><?php echo $share["profile_photo"]; ?>" class="img-circle" height="50" width="50" style="float:left">
                            <span class="share-block-username"><?= $share["nickname"]?></span>
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
                            <div class="share-block-text">
                                <?php echo Share::substrText($share["text"],500); ?>
                                <?php
                                    if($share["attach"]!=""){
                                     echo '<img src="/images/share/thumbs/'. $share["date_folder"]."/".Url::base().$share["attach"].'" class="img-responsive img-rounded img-share">';

                                    }
                                ?>

                            </div>
                        </div>
                    </a>

                    <div class="share-block-icons">
                        <a class="cursor"  id="like-share" data-toggle="modal" data-target="#login_alert_modal">
                            <img id="share-img-<?php echo $share["id"]; ?>" src="/images/icons/share/like.png">
                            <span id="like_count_<?= $share["id"]?>"><?= $share["like_count"]>0?$share["like_count"]:'';?></span>
                        </a>

                        <a>
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

            <?php endforeach;?>
            <?php
            if ($pages) {
                // display pagination
                echo '<div class="text-center">';
                echo \yii\widgets\LinkPager::widget([
                    'pagination' => $pages,
                    'options' => [
                        'class' => 'pagination',
                        'style' => 'display:inline-block'
                    ],
                    'maxButtonCount' => 6
                ]);
                echo '</div>';
            }
            ?>
        </div>
        <div class="col-md-3 col-lg-3">
            <div class="ads-block1 hidden-xs hidden-sm">
                <a href="http://bescore.com?ref=alochat" target="_blank">
                    <img class="inline-block" src="/images/livescore_ads.jpg" style="width:100%;" align="middle">
                </a>
            </div>

            <div class="ads-block1 hidden-xs hidden-sm">
                <a href="http://alochat.com">
                    <img class="inline-block" src="/images/alochat_ads.jpg" style="width:100%;margin-bottom: 10px;" align="middle">
                </a>
            </div>
        </div>
    </div>
</div>
