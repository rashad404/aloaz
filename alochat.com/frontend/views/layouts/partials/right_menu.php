<?php
use yii\helpers\Url;
?>
<img class="right-menu-back" src="/images/icons/sprite/left-ico.png">
<div class="right-menu-user">
    <div class="hidden-xs pull-left">
        <a href="<?= Url::to(["/u/".Yii::$app->user->id])?>">
            <img class="img-circle"
             src="<?php echo  Yii::$app->user->identity->profile_photo!=""?Url::base() . Yii::$app->user->identity->profile_photo: Url::base() . \Yii::$app->params['defaultProfilePicture_'.Yii::$app->user->identity->sex]?>" width="40" height="40">
        </a>
    </div>
    <div class="right-menu-user-about">
        <a href="<?= Url::to(["/u/".Yii::$app->user->id])?>"><?php echo Yii::$app->user->identity->nickname; ?></a> <br />
        <span class="right-menu-user-city"><?php if(Yii::$app->user->identity->city_id!=0) echo \common\models\City::getCityName(Yii::$app->user->identity->city_id)?> <?php if(Yii::$app->user->identity->country_id!=0) echo ", ".\common\models\Country::getCountryName(Yii::$app->user->identity->country_id)?>
           <br /><a href="<?= Url::to(["/coins"])?>"> <?= Yii::$app->user->identity->coins." ".Yii::t('app','Coins')?></a>
        </span>

    </div>
    <div class="clearfix"></div>
</div>
<div class="right-menu-items">
    <div class="right-menu" >
        <a href="<?= Url::to(["/profile/friends/".Yii::$app->user->id])?>"> <?= Yii::t('app','Friends')?></a>
        <a href="<?= Url::to(["/site/search"])?>"> <?= Yii::t('app','Search')?> </a>
        <a href="<?= Url::to(["/profile/visitors"])?>"> <?= Yii::t('app','Visitors')?> </a>
        <a href="<?= Url::to(["/profile/liked"])?>"> <?= Yii::t('app','I like')?></a>
        <a href="<?= Url::to(["/profile/like"])?>"> <?= Yii::t('app','Likes')?></a>
        <a href="<?= Url::to(["/profile/mutual-likes"])?>"> <?= Yii::t('app','Mutual likes')?></a>
        <a href="<?= Url::to(["/profile/gifts/".Yii::$app->user->id])?>"> <?= Yii::t('app','My gifts')?></a>
        <a href="<?= Url::to(["/coins"])?>"> <?= Yii::t('app', 'Coins services')?></a>
        <a href="<?= Url::to(['/profile/settings'])?>"><?= Yii::t('app','Profile settings')?> </a>
        <a href="<?= Url::to(['/profile/site-settings'])?>"><?= Yii::t('app','Settings')?></a>
        <a href="<?= Url::to(["/site/logout"])?>"  data-method="post"><?= Yii::t('app','Logout')?></a>

    </div>
</div>