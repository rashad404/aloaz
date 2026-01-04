<?php
/**
 * Created by PhpStorm.
 * User: Elvin
 * Date: 18.04.2015
 * Time: 16:28
 */
use yii\helpers\Url;
use common\models\User;

?>
<div class="main">

    <h2>
        <?= Yii::t('app', '{site_name} is the faster-growing online discovery network', ['site_name'=>'Alo Chat']) ?>
    </h2>

    <img class="devices img-responsive" src="<?=Url::base().'/images/devices.jpg'?>"/>

    <h2><?=Yii::t('app','Socialize with us')?></h2>
    <p><?=Yii::t('app','Add our application to your favourite social network')?></p>

    <ul class="social">
        <li><a href="#" class="social-button facebook"></a></li>
        <li><a href="#" class="social-button twitter"></a></li>
        <li><a href="#" class="social-button vkontakte"></a></li>
        <li><a   href="#"  class="social-button google"></a></li>
    </ul>
</div>
