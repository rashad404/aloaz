<?php
use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
$this->title =Yii::t('app','Change nickname');
?>



<div class="profile-page-container profile-page-main" style="padding-top: 0px !important;background-color: transparent;border: none;">

    <div class="row title-block" id="user-filter1">
        <div class="col-md-12">
            <div class="pull-left">  <?= $this->title;  ?></div>

        </div>
    </div>
    <div class="row" style="background-color: #FFF;padding: 20px;">
        <?= Yii::t('app','Coins in your acount')?> :  <?= Yii::$app->user->identity->coins?><br />
        <?= Yii::t('app','Cost of the service')?>: <?= Yii::$app->params["changeNicknameCoin"]?> bal<br /><br />
        <div class="settings-content">
            <?php $form2 = ActiveForm::begin(['id' => 'form-nick-change']); ?>

            <?= $form2->field($form, 'nickname')->textInput() ?>

            <hr>
            <?= $form2->field($form, 'password')->passwordInput() ?>
            <hr/>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Change'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <p>

         <b><?= Yii::t('app','Note')?></b>   <?= Yii::t('app','Loqin AloChat qaydalarına uyğun olmalıdır. Əks halda sayta girişiniz məhdudlaşdırılacaq.')?>
        </p>

    </div>



</div>
