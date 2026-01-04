<?php
use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
$this->title = Yii::t('app', 'Remove account');
?>



<div class="profile-page-container profile-page-main" style="padding-top: 0px !important;background-color: transparent;border: none;">

    <div class="row title-block" id="user-filter1">
        <div class="col-md-12">
            <div class="pull-left">  <?= $this->title;  ?></div>

        </div>
    </div>
    <div class="row" style="background-color: #FFF;padding: 20px;">
        <?= Yii::t('app','Coins in your acount')?>  :  <?= Yii::$app->user->identity->coins?><br />
        <?= Yii::t('app','Cost of the service')?>: <?= Yii::$app->params["minCoinsForVipUser"]?> <?= Yii::t('app','Coin')?><br /><br />
        <div class="settings-content">
            <?= Yii::t('app','Are you sure remove account?')?>
            <?php
                $sc = md5(md5("delete-nick".Yii::$app->user->id.Yii::$app->user->identity->nickname.Yii::$app->user->id."delete-nick"));
            ?>
            <a href="<?php echo \yii\helpers\Url::to(['/coins/delete-nick?sc='.$sc])?>" class="btn btn-danger"><?= 'Bəli'; ?></a>
            <a href="<?php echo \yii\helpers\Url::to(['/coins/index'])?>" class="btn btn-default"><?= 'Xeyr'?></a>

        </div>
        <p>

         </p>

    </div>



</div>
