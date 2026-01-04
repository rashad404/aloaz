<?php
use yii\helpers\Html;
use frontend\assets\HomePageAsset;
use yii\helpers\Url;
/* @var $this \yii\web\View */
/* @var $content string */
HomePageAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <?php
        $this->registerMetaTag(['name' => 'keywords',
            'content' => Yii::t('app', 'dating,online chat,messenger,online network, free online dating service')]);

        $this->registerMetaTag(['name' => 'description',
            'content' => Yii::t('app', 'AloChat is a great platform to meet new people in your area and around the world.')]);
        ?>
        <!--[if lt IE 9]>
        <script src="<?= Url::base() ?>/js/html5shiv.js"></script>
        <script src="<?= Url::base() ?>/js/respond.min.js"></script>
        <![endif]-->

        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
<?php $this->beginBody() ?>
    <div class="container-fluid">
        <div class="container" id="main">

            <?= $this->render('/layouts/partials/home_header'); ?>


        <?php echo $content; ?>
        </div>
        <footer>
            <div class="footer-up">
                <div class="container">
                    <img src="<?= Url::base()?>/images/alochat_logo2.png" class="logo-footer">
                    <div class="footer-nav">
                        <a href="<?php echo Url::to(["/site/about"])?>"><?= Yii::t('about', 'About Us') ?></a>
                        <a href="<?php echo Url::to(["/site/privacy-policy"])?>"><?= Yii::t('app', 'Privacy & Policy') ?></a>
                        <a href="<?php echo Url::to(['/site/contact'])?>"><?= Yii::t('app', 'Contact') ?></a>
                    </div>
                </div>
            </div>
            <div class="footer-down">
                <div class="text-center copyright">
                    Copyright © 2015 Alo Chat. All rights reserved.
                </div>
            </div>
        </footer>

    </div>
<?php $this->endBody() ?>

    </body>
</html>
<?php $this->endPage() ?>
