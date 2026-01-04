<?php
use frontend\assets\SimpleAsset;
use yii\widgets\ActiveForm;
use \yii\helpers\Url;
use \yii\helpers\Html;
use \common\models\Share;


SimpleAsset::register($this);

$this->title = $user["nickname"];
$this->title.= $share["text"]!=""?" ".mb_substr($share["text"],0,20,'UTF-8'):'';
?>

<div class="row">
    <div class="col-md-9 col-lg-9 col-xs-12 col-sm-9">
        <div class="row">
            <?= \frontend\components\LoginProfileLeftBarWidget::widget(['actionName' => Yii::$app->controller->action->id]);?>

        </div>
        <div class="row">
            <div >

                <div class="col-md-12" style="background-color: #f5f5f5">
                    <div class="row profile-title-block" id="user-filter1">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs profile-nav-tabs">
                                <li role="presentation" class="active"><a href="<?php echo Url::to(['/profile/timeline/'.$user["id"]])?>">Timeline</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="center-block col-md-12" style="background-color: #f5f5f5;border: 0px !important;margin-right: -15px; margin-left: -15px;">

                    <!--------------->
                    <div class="row"> <!--row-->

                        <?php
                        $i=1;$st= '';
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
                                                    <?php echo Share::substrText($share["text"],500); ?>
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
                    </div>                    <!--------------->


                    <?php
                    if ($pages) {
                        // display pagination
                        echo '<div class="text-center">';

                        echo \yii\widgets\LinkPager::widget([
                            'pagination' => $pages,
                        ]);
                        echo '</div>';
                    }
                    ?>
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


