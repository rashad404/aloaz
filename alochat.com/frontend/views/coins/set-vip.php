<?php
use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
$this->title = Yii::t('app', 'Vip User');
?>



<div class="profile-page-container profile-page-main" style="padding-top: 0px !important;background-color: transparent;border: none;">

    <div class="row title-block" id="user-filter1">
        <div class="col-md-12">
            <div class="pull-left">  <?= $this->title;  ?></div>

        </div>
    </div>
    <div class="row" style="background-color: #FFF;padding: 20px;">
        <?= Yii::t('app','Coins in your acount')?>:  <?= Yii::$app->user->identity->coins?><br />
        <?= Yii::t('app','Cost of the service')?>: <?= Yii::$app->params["minCoinsForVipUser"]?> <?= Yii::t('app','Coin')?><br /><br />
        <div class="settings-content">
            <?= Yii::t('app','{coin} coins will be deducted from your account to become a VIP user.',['coin' => Yii::$app->params["minCoinsForVipUser"]])?>

            <a href="<?php echo \yii\helpers\Url::to(['/profile/set-vip'])?>" class="btn btn-success"><?= Yii::t('app','Become a vip user')?></a>

        </div>
        <p>

         </p>

    </div>



</div>
