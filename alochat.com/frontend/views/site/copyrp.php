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
                        <div class="site-reset-password">
                            <h3><?= Html::encode($this->title) ?></h3>

                            <p><?=Yii::t('app','Please choose your new password:');?></p>

                            <div class="row">
                                <div class="col-lg-5">
                                    <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                                    <?= $form->field($model, 'password')->passwordInput() ?>
                                    <div class="form-group">
                                        <?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary']) ?>
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







