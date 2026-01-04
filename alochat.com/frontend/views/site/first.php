<?php


/* @var $this yii\web\View */
$this->title = 'Alochat.com - Free dating,Online dating, dating singles, best online free dating site';


?>

<?= $this->render('partials/nav.php') ?>

<section class="ss-window" id="section1">
    <?= $this->render('partials/section1.php', ['signupForm' => $signupForm]) ?>
</section>

<!--<section class="ss-window hidden-xs" id="section2">-->
<!--</section>-->

<section class="ss-window" id="section3">
    <?= $this->render('partials/section3.php') ?>
</section>

<section class="ss-window hidden-xs" id="section4">
    <?= $this->render('partials/section4.php') ?>
</section>

<section class="ss-window" id="section5">
    <?= $this->render('partials/section5.php',['loginForm'=>$loginForm]) ?>
</section>
<input type="hidden"  id="scrollToBottom" value="<?=$scrollToBottom?>">