<?php
/* @var $this yii\web\View */
/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;

$this->title = 'Alochat.com - Free dating,Online dating, dating singles, best online free dating site';
?>

<div id="main" class="container-fluid">
    <div class="row main1">
        <div class="col-md-12  col-lg-12 col-xs-12 col-sm-12">

            <hr class="hr-full">


            <div class="row main-register">

                <?php $form = ActiveForm::begin(['id' => 'form-signup','enableClientValidation' => false,
                    'enableAjaxValidation' => false]); ?>

                <input type="text" name="nickname" style="display: none">
                <input type="password" name="password" style="display: none">

                <div class="col-md-6 col-xs-12 col-sm-12 col-lg-6 reg-from" >
                    <div class="home-title">
                         <?php echo Yii::t('app', 'Signup')?>
<!--                        --><?/*= var_dump($signupForm->getErrors());*/?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="row registration-block">

                        <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 reg-input-block">
                            <div class="row">
                                <div class="col-md-3 col-sm-3 col-lg-3 col-xs-3 reg-label-div"><label class="reg-input-label"><?= Yii::t('app', 'Nick')?></label></div>
                                <div class="col-md-9 col-sm-9 col-lg-9 col-xs-9">
                                    <?= $form->field($signupForm, 'nickname')
                                        ->textInput(['placeholder' => Yii::t('app', 'Nick'), 'value' => '','autocomplete'=>"off",'class' => 'form-control reg-form-control'])->label(false) ?>                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 reg-input-block">
                            <div class="row" >
                                <div class="col-md-3 col-sm-3 col-lg-3 col-xs-3 reg-label-div"><label  class="reg-input-label"><?= Yii::t('app', 'Password')?></label></div>
                                <div class="col-md-9 col-sm-9 col-lg-9 col-xs-9">
                                    <?= $form->field($signupForm, 'password')
                                        ->passwordInput(['placeholder' => Yii::t('app', 'Password'),'class' => 'form-control reg-form-control'])
                                        ->label(false)
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 reg-input-block">
                            <div class="row">
                                <div class="col-md-3 col-sm-3 col-lg-3 col-xs-3 reg-label-div"><label  class="reg-input-label"><?= Yii::t('app', 'Name')?></label></div>
                                <div class="col-md-9 col-sm-9 col-lg-9 col-xs-9">
                                    <?= $form->field($signupForm, 'full_name')
                                        ->textInput(['placeholder' => Yii::t('app', 'Name'),'class' => 'form-control reg-form-control'])->label(false) ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 reg-input-block">
                            <div class="row">
                                <div class="col-md-3 col-sm-3 col-lg-3 col-xs-3 reg-label-div"><label  class="reg-input-label"><?= Yii::t('app', 'Email')?></label></div>
                                <div class="col-md-9 col-sm-9 col-lg-9 col-xs-9">
                                    <?= $form->field($signupForm, 'email')
                                        ->textInput(['placeholder' => Yii::t('app', 'Email'),'class' => 'form-control reg-form-control'])->label(false) ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 reg-input-block">
                            <div class="row">
                                <div class="col-md-3 col-sm-3 col-lg-3 col-xs-3 reg-label-div"><label  class="reg-input-label"><?= Yii::t('app','Birthday')?></label></div>
                                <div class="col-md-9 col-sm-9 col-lg-9 col-xs-9" style="margin-bottom: -15px;">
                                    <div class="row">
                                        <!-- /input-group -->
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="padding-right:3px; ">
                                            <?= $form->field($signupForm, 'b_day')->dropDownList(
                                                User::getDays(),
                                                [
                                                    'class' => 'form-select'
                                                ]

                                            )->label(false) ?>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"  style="padding-right:3px;padding-left: 3px; ">
                                            <?= $form->field($signupForm, 'b_month')->dropDownList(
                                                User::getMonths(),
                                                [
                                                    'class' => 'form-select'
                                                ]
                                            )->label(false) ?>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"  style="padding-left:3px; ">
                                            <?= $form->field($signupForm, 'b_year')->dropDownList(
                                                User::getYears(),
                                                [
                                                    'class' => 'form-select'
                                                ]

                                            )->label(false) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 reg-input-block">
                            <div class="row">
                                <div class="col-md-3 col-sm-3 col-lg-3 col-xs-3 reg-label-div"><label class="reg-input-label"><?= Yii::t('app', 'Sex')?></label></div>
                                <div class="col-md-9 col-sm-9 col-lg-9 col-xs-9">
                                    <label class="radio">
                                        <input id="radio1" type="radio" name="SignupForm[sex]" value="<?= User::SEX_MAN?>" checked>
                                        <span class="outer"><span class="inner"></span></span><?= Yii::t('app', 'Man')?></label>
                                    <label class="radio">
                                        <input id="radio2" type="radio" name="SignupForm[sex]" value="<?= User::SEX_WOMAN?>">
                                        <span class="outer"><span class="inner"></span></span><?= Yii::t('app', 'Woman')?></label>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                                 <button type="submit" class="btn col-md-4 col-xs-12 login-btn pull-right" name="signup-button"><?= Yii::t('app','Signup')?></button>
                         </div>
                        <div class="clearfix"></div>
                        <div class="col-md-12 pull-right" style="text-align: right;margin-top: 10px;">
                            <span style="font-size: 12px; color: #757575">
                                <?= Yii::t('app','License agreement')?>
                            </span>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-9 col-md-offset-3">
                            <div class="" style="margin:15px 0px;">

                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6 facebook-block" style="margin-top: 0px;padding-right: 3px;">
                                    <a href="<?= Url::to(['/site/auth?authclient=facebook'])?>">
                                        <div class="container-fluid facebook-block-full">
                                            <div class="facebook-block-left"><img src="/images/icons/f.png"></div>
                                            <div class="facebook-block-right"><span class="hidden-xs hidden-sm"><!--Sign up with--> </span>Facebook</div>
                                            <div class="clearfix"></div>
                                        </div>
                                     </a>
                                 </div>
                                <div class="col-lg-6 col-md-6 col-xs-6 col-sm-6 twitter-block" style="padding-left: 3px;">
                                       <a href="<?= Url::to(['/site/auth?authclient=google'])?>">
                                       <div class="container-fluid twitter-block-full">
                                           <div class="twitter-block-left"><img src="/images/icons/g.png"></div>
                                           <div class="twitter-block-right"><span class="hidden-xs hidden-sm"><!--Sign up with--> </span>Google  </div>                             </div>
                                           <div class="clearfix"></div>
                                       </div>
                                     </a>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>

