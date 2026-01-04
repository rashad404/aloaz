<?php
/**
 * Created by PhpStorm.
 * User: Elvin
 * Date: 18.04.2015
 * Time: 16:28
 */
use yii\helpers\Url;

?>
<div class="main hidden-xs">

    <h2><?= Yii::t('app', 'Wherever you are, stay connected with {site_name}!', ['site_name' => 'Alo Chat']) ?></h2>
    <h4><?= Yii::t('app', 'Now on IOS, Android and mobile browsers') ?></h4>

    <div class="logo clearfix hidden-xs"></div>

    <ul class="mobile-links hidden-xs">
        <li>
            <a class="app-store" href="#"></a>
        </li>
        <li>
            <a class="play-store" href="#"></a>
        </li>
        <li>
            <a href="#" class="mobile"></a>
        </li>

    </ul>
</div>