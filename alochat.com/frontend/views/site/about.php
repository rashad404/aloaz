<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'Alochat.com - Free dating,Online dating, dating singles, best online free dating site';
?>

<div id="main" class="container-fluid">
    <div class="row main1">
        <div class="col-md-12  col-lg-12 col-xs-12 col-sm-12">

            <hr style="width: 100%; background-color: #82c6f0; height: 1px; ">


            <div class="row" style="margin: 20px 0;">

                 <div class="col-md-11 col-xs-12 col-sm-12 col-lg-11" style="padding-left: 60px;">


                         <div style="color: #78c2f0;font-size: 20px; font-weight: bold; margin-bottom: 20px;">
                            <?= Yii::t('about', 'About Us') ?>
                        </div>
                        <div style="font-size: 18px;">
                            <?= Yii::t('about','AloChat is a great platform to meet new people in your area and around the world.')?>
                        </div>
                        <div style="line-height: 2.5;font-size: 16px;word-wrap: break-word">
                            <?= Yii::t('about','about_us_text')?>
                        </div>
                        <div style="color: #78c2f0;font-size: 20px; font-weight: bold; margin-bottom: 14px;margin-top: 20px">
                            <?= Yii::t('app', 'Contact') ?>
                        </div>
                        <div style="line-height: 2.5;font-size: 16px;">
                            <?= Yii::t('app','You can contact us via the following e-mail:<br />{email}',['email'=>'info@alochat.com']); ?>
                        </div>



                </div>
            </div>

        </div>
    </div>
</div>
