<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

$this->title = Yii::t('app','Request password reset');
$this->params['breadcrumbs'][] = $this->title;

 ?>

<div id="main" class="container-fluid">
    <div class="row main1">
        <div class="col-md-12  col-lg-12 col-xs-12 col-sm-12">

            <hr class="hr-full">


            <div class="row main-register">




                <div class="col-md-8 col-xs-12 col-sm-12 col-lg-8 reg-from" >
                    <div class="home-title">
                        <?php echo $this->title;?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="row registration-block">
                        <div class="site-request-password-reset">

                            <p><?= Yii::t('app', 'Please fill out your email. A link to reset password will be sent there.'); ?></p>

                            <div class="row">
                                <div class="col-lg-12">
                                    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email'])->label(false) ?>
                                        </div>
                                        <div class="col-md-4">
                                            <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn login-btn','style' => 'float:left']) ?>
                                        </div>
                                    </div>

                                    <?php ActiveForm::end(); ?>
                                </div>
                                <div class="hidden-xs hidden-sm hidden-md" style="height: 300px;"></div>
                                <div class="hidden-xs hidden-sm hidden-lg" style="height: 300px;"></div>
                            </div>


                        </div>

                    </div>



                </div>
            </div>

        </div>
    </div>
</div>







