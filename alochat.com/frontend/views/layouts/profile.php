<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;
use frontend\components\LanguageChangeWidget;
use yii\helpers\Url;
use frontend\components\LeftBarWidget;
 use yii\widgets\Menu;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="<?= Yii::$app->language; ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!--    <meta charset="<?/*= Yii::$app->charset */?>">
-->    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
<div id="right-menu">
    <?= $this->render('/layouts/partials/right_menu'); ?>
</div>
<div class="wrap" id="wrapper">

    <?php
    $messagesLabel = Yii::t('app', 'Messages');
    if(!Yii::$app->user->isGuest){
        $newMsgCount = intval(Yii::$app->user->identity->getNewMessagesCount(Yii::$app->user->id));
    } else {
        $newMsgCount = 0;
    }

    $headerPositionStyle ='';
    $controllerId = $this->context->id;
    $actionId = $this->context->action->id;

    if($controllerId == "messages" && $actionId =="view"){
        $headerPositionStyle = "position:absolute;";
    }
    ?>
    <?= $this->render('partials/header.php'); ?>
</div>

    <div class="container profile-container" id="top">

        <div class="clearfix"></div>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>

        <?= $this->render('/site/partials/modals/coins_modal'); ?>
        <?php //////////////////////////////////////////////////////////////////////////////////////////////////////////?>

            <div class="row">
                <div class="col-md-3 col-lg-3 col-sm-3 col-xs-12 profile-left">
                     <?= \frontend\components\ProfileLeftBarWidget::widget(['actionName' => Yii::$app->controller->action->id]);

                    ?>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-9 col-xs-12 profile-center">
                    <?= $content; ?>
                </div>
                <div class="col-md-3 col-lg-3 hidden-xs hidden-sm profile-right">
                    <div class="right-block">
                        <?= \frontend\components\LastShareBarWidget::widget(); ?>
                    </div>


                    <!--<div class="right-block">
                        <img class="inline-block" src="/images/livescore_ads.png" style="width:100%;" align="middle">
                    </div>-->
                </div>
            </div>
        <?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////?>
        <div class="qp-ui-mask-modal"></div>

    </div>
<?= $this->render('/profile/partials/profile_footer.php'); ?>

<?= $this->render('/site/partials/modals/aboutus_modal'); ?>
<?= $this->render('/site/partials/modals/privacy_modal'); ?>
<?= $this->render('/site/partials/modals/contact_modal'); ?>
<?php $this->endBody() ?>

<script>
    $.ajaxSetup({
        data: <?= \yii\helpers\Json::encode([
            \yii::$app->request->csrfParam => \yii::$app->request->csrfToken,
        ]) ?>
    });
</script>
</body>
</html>
<?php $this->endPage() ?>
