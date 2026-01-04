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
        <header>
            <div class="row">
                <div class="col-md-2 col-xs-2">
                    <img src="<?= Url::base()?>/images/alochat_logo.png" class="logo">
                </div>
                <div class="col-md-10 col-xs-10 header-right" style="/*border: 1px solid red;*/">

                    <div class="social">
                        <div class="languages">
                            <ul class="nav nav-pills">
                                <li role="presentation" ><a href="#">Azərbaycan</a></li>
                                <li role="presentation"><a href="#">Русский</a></li>
                                <li role="presentation" class="active"><a href="#">English</a></li>
                                <li role="presentation"><a href="#">Türkçe  </a></li>
                            </ul>
                        </div>
                        <div style="float: right">
                            <div class="social-twitter">
                                <img src="<?= Url::base()?>/images/icons/twitter.png">
                            </div>
                            <div class="social-facebook">
                                <img src="<?= Url::base()?>/images/icons/facebook.png">
                            </div>

                        </div>


                        <div class="clearfix"></div>
                    </div>

                </div>
            </div>
        </header>
        <div class="clearfix"></div>
        <div id="main" class="container-fluid" style="margin-top: 10px;padding: 0px;">
            <div class="row">
                <div class="col-md-9 hidden-xs hidden-sm">
                    <div class="" style="height: 1px; width:100%; background-color: #82c6f0; text-align: right;color:#78c2f0;font-size: 18px;">
          <span style="background-color: #f5f5f5; position: relative; top: -0.5em;">
           &nbsp;&nbsp; ALO chat-ın aktiv istifadəçiləri
          </span>
                    </div>
                    <!-- <?php
                    /*                            $img_array = [];
                                                for($i=1;$i<=10;$i++):
                                                */?>
                            <div class="col-md-15  col-sm-3 col-xs-6" style="height: 140px;">
                                <div style="float: left;">
                                    <img src="<?/*= Url::base()*/?>/images/user/1128728/thumbs/1128728_0.jpg" alt="..." class="img-circle img-responsive">

                                </div>
                                <div style="float: left:width:10px;">
                                    <span class="online">
                                     <div class="status online"></div>
                                     </span>
                                </div>
                                <div class="clear"></div>

                            </div>
                            --><?php /* endfor; */?>
                    <ul class="ch-grid">
                        <li>
                            <div class="ch-item ch-img-1">
                                <div class="ch-info">
                                    <h5>Baki</h5>
                                    <p><a href="http://drbl.in/eOPF" style="color: #FFF;font-size: 15px;">Yusif Nesibli</a></p>
                                    <span>22</span>

                                </div>
                            </div>

                        </li>
                        <li>
                            <div class="ch-item ch-img-2">
                                <div class="ch-info">
                                    <h3>Common Causes of Stains</h3>
                                    <p>by Antonio F. Mondragon <a href="http://drbl.in/eKMi">View on Dribbble</a></p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="ch-item ch-img-3">
                                <div class="ch-info">
                                    <h3>Pink Lightning</h3>
                                    <p>by Charlie Wagers <a href="http://drbl.in/ekhp">View on Dribbble</a></p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="ch-item ch-img-2">
                                <div class="ch-info">
                                    <h3>Common Causes of Stains</h3>
                                    <p>by Antonio F. Mondragon <a href="http://drbl.in/eKMi">View on Dribbble</a></p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="ch-item ch-img-3">
                                <div class="ch-info">
                                    <h3>Pink Lightning</h3>
                                    <p>by Charlie Wagers <a href="http://drbl.in/ekhp">View on Dribbble</a></p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="ch-item ch-img-1">
                                <div class="ch-info">
                                    <h5>Yusif Nesibli</h5>
                                    <p>by Angela Duncan <a href="http://drbl.in/eOPF">View on Dribbble</a></p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="ch-item ch-img-2">
                                <div class="ch-info">
                                    <h3>Common Causes of Stains</h3>
                                    <p>by Antonio F. Mondragon <a href="http://drbl.in/eKMi">View on Dribbble</a></p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="ch-item ch-img-3">
                                <div class="ch-info">
                                    <h3>Pink Lightning</h3>
                                    <p>by Charlie Wagers <a href="http://drbl.in/ekhp">View on Dribbble</a></p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="ch-item ch-img-2">
                                <div class="ch-info">
                                    <h3>Common Causes of Stains</h3>
                                    <p>by Antonio F. Mondragon <a href="http://drbl.in/eKMi">View on Dribbble</a></p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="ch-item ch-img-3">
                                <div class="ch-info">
                                    <h3>Pink Lightning</h3>
                                    <p>by Charlie Wagers <a href="http://drbl.in/ekhp">View on Dribbble</a></p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-md-3 col-sm-8 col-xs-12">
                    <div class="login-panel">
                        <div class="login-panel-title">
                            <div class="login-title">Login</div>
                            <div class="register-title">
                                <div class="register-title-btn">Registration</div>
                            </div>
                            <div class="clearfix"></div>

                        </div>
                        <div class="login-panel-body">
                            <label><span class="login-panel-label">Username, email or phone number</span></label>
                            <input type="text" name="Nickname" class="form-control" placeholder="Username">
                            <br />
                            <label> <span class="login-panel-label">Password</span></label>
                            <input type="password" name="Password" class="form-control" placeholder="Username">
                            <br />
                            <p style="float: left">Parolu unutdunuz? </p>
                            <a class="btn btn-large login-btn">Login</a>
                            <div class="clearfix"></div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9">

                    <div class="row">

                        <div class="col-md-12 share-block" style="background-color: #FFF;padding-left: 0px; padding-right: 0px;margin-bottom: 20px;">
                            <div class="" style="padding: 15px;">
                                <img src="<?= Url::base()?>/images/user/1128728/thumbs/1128728_0.jpg" class="img-circle" height="50" width="50" style="float:left">
                                <span style="color: #292f33; font-size: 15px;margin-left: 20px;padding-top:10px;float: left;font-weight: bold">Jasmin</span>
                                    <span style="float: right;padding-top: 10px;color: #8899a6; font-size: 12px;">12:00pm    01 november 2015
                                             <span class="online">
                                             <div class="status online"></div>
                                             </span>
                                     </span>
                                <div class="clearfix"></div>
                                <div class="share-block-text" style="margin-left: 60px;color: #292f33; font-size: 14px;line-height: 30px;">
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled.
                                </div>
                            </div>
                            <div style="background-color: #eff5f9;width: 100%;padding-left:65px; ">
                                <textarea style="width: 95%; height: 67px;margin: 15px 20px 20px 0px;;"></textarea>
                                <div>
                                    <div class="share-block-icons" style="padding-bottom: 10px;float: left">
                                        <img src="/images/icons/share/like.png" style="margin-right: 20px;">
                                        <img src="/images/icons/share/comment.png">
                                    </div>
                                    <div style="float: right;margin-right: 40px;padding-bottom: 10px;">
                                        <a class="btn btn-large comment-btn">Send</a>

                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-12 share-block" style="background-color: #FFF;padding-left: 0px; padding-right: 0px;margin-bottom: 20px;">
                            <div class="" style="padding: 15px;">
                                <img src="<?= Url::base()?>/images/user/1128728/thumbs/1128728_0.jpg" class="img-circle" height="50" width="50" style="float:left">
                                <span style="color: #292f33; font-size: 15px;margin-left: 20px;padding-top:10px;float: left;font-weight: bold">Jasmin</span>
                                    <span style="float: right;padding-top: 10px;color: #8899a6; font-size: 12px;">12:00pm    01 november 2015
                                             <span class="online">
                                             <div class="status online"></div>
                                             </span>
                                     </span>
                                <div class="clearfix"></div>
                                <div class="share-block-text" style="margin-left: 60px;color: #292f33; font-size: 14px;line-height: 30px;">
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled.
                                    <img src="<?= Url::base()?>/images/share-test.png" class="img-responsive img-rounded">
                                </div>
                            </div>
                            <div style="background-color: #eff5f9;width: 100%;padding-left:65px; ">
                                <textarea style="width: 95%; height: 67px;margin: 15px 20px 20px 0px;;"></textarea>
                                <div>
                                    <div class="share-block-icons" style="padding-bottom: 10px;float: left">
                                        <img src="/images/icons/share/like.png" style="margin-right: 20px;">
                                        <img src="/images/icons/share/comment.png">
                                    </div>
                                    <div style="float: right;margin-right: 40px;padding-bottom: 10px;">
                                        <a class="btn btn-large comment-btn">Send</a>

                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-12 share-block" style="background-color: #FFF;padding-left: 0px; padding-right: 0px;margin-bottom: 20px;">
                            <div class="" style="padding: 15px;">
                                <img src="<?= Url::base()?>/images/user/1128728/thumbs/1128728_0.jpg" class="img-circle" height="50" width="50" style="float:left">
                                <span style="color: #292f33; font-size: 15px;margin-left: 20px;padding-top:10px;float: left;font-weight: bold">Jasmin</span>
                                    <span style="float: right;padding-top: 10px;color: #8899a6; font-size: 12px;">12:00pm    01 november 2015
                                             <span class="online">
                                             <div class="status online"></div>
                                             </span>
                                     </span>
                                <div class="clearfix"></div>
                                <div class="share-block-text" style="margin-left: 60px;color: #292f33; font-size: 14px;line-height: 30px;">
                                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled.
                                </div>
                            </div>
                            <div style="width: 100%;padding-left:65px; ">
                                <!--                                    <textarea style="width: 95%; height: 67px;margin: 15px 20px 20px 0px;"></textarea>
                                -->                                    <div style="padding: 15px 20px 20px 0px;">
                                    <div class="share-block-icons" style="padding-bottom: 10px;float: left">
                                        <img src="/images/icons/share/smile.png" style="margin-right: 20px;">
                                        <img src="/images/icons/share/photo.png" style="margin-right: 20px;">
                                        <img src="/images/icons/share/gift.png" style="margin-right: 20px;">
                                        <img src="/images/icons/share/like.png" style="margin-right: 20px;">
                                        <img src="/images/icons/share/comment.png">
                                    </div>

                                    <div class="clearfix"></div>
                                </div>
                            </div>

                        </div>


                    </div>
                </div>
                <div class="col-md-3">

                    <div class="ads-block">
                        <img class="inline-block" src="/images/livescore_ads.png" style="width:100%;" align="middle">
                    </div>

                    <div class="ads-block">
                        <img class="inline-block" src="/images/alochat_ads.png" style="width:100%;" align="middle">
                    </div>
                </div>
            </div>
        </div>

    </div>
    <footer>
        <div class="footer-up">
            <div class="container">
                <img src="<?= Url::base()?>/images/alochat_logo2.png">
                <div class="footer-nav">
                    <a>About us</a>
                    <a>Privacy & Policy</a>
                    <a>Contacts</a>
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
