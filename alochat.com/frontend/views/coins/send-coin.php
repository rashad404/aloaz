<?php
use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
$this->title = Yii::t('app', 'Send Coin');
?>



<div class="profile-page-container profile-page-main" style="padding-top: 0px !important;background-color: transparent;border: none;">

    <div class="row title-block" id="user-filter1">
        <div class="col-md-12">
            <div class="pull-left">  <?= $this->title;  ?></div>

        </div>
    </div>
    <div class="row" style="background-color: #FFF;padding: 20px;">
        <?= Yii::t('app','Coins in your acount')?>:  <?= Yii::$app->user->identity->coins?><br />
        <?= Yii::t('app','The maximum coins you can send')?>: <?= $maxCoin ?> <?= Yii::t('app','Coin')?><br /><br />
        <div class="settings-content">
            <?php $form2 = ActiveForm::begin(['id' => 'form-nick-change']); ?>

            <?= $form2->field($form, 'nickname')->textInput() ?>

            <hr>
            <?= $form2->field($form, 'coin')->textInput() ?>
            <hr/>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <p>

         <b><?= Yii::t('app','Note');?>:</b>
            <?php  echo Yii::t('app','Comission: {percent}',["percent" => '20%'])?>
        </p>

    </div>



</div>
