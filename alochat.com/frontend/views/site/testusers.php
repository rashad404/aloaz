<?php
/**
 * User: Yusif
 * Date: 7/13/2015
 * Time: 11:09 AM
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use frontend\assets\DiscoveryAsset;
use common\models\User;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Users');
DiscoveryAsset::register($this);

// echo count($users)."<br />";
?>
<style type="text/css">
    .profile-page-container{
        background-color: transparent;
        border: none;
    }
</style>

<?= $this->render('partials/set_vip_user_modal'); ?>
<?php
if($_SERVER["REMOTE_ADDR"] == '37.32.67.22'){
    ?>
    <div style="display: block">
        <?= 'Opened: '.$timeFerq?>
    </div>
<?php } ?>
<div class="profile-page-container profile-page-main" style="padding-top: 0px !important;background-color: transparent;border: none;">
    <?php if(Yii::$app->user->identity->verify!=1):?>
        <div class="alert alert-danger" role="alert" style="margin: 0px -15px 10px !important;}">
            <i class="glyphicon glyphicon-warning-sign"></i>
            Sizin nömrəniz təsdiq olunmayıb zəhmət olmasa saytdan tam yararlanmaq üçün <a href="<?= Url::to(["/profile/verify"])?>">təsdiq edin</a></div>
    <?php endif;?>
    <div class="row" id="user-filter1" style="color:#37474f;padding-top: 14px;font: 18px Roboto,Helvetica,Arial,sans-serif;margin-bottom: 10px;border: 1px solid #e3e3e3;height: 50px;background-color: #FFF !important;">
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
            <?= " Online:".(User::getOnlineUserCount())?>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6" style="text-align: right">
            <?php
            $usersFrom = '';
            if($userFilter->countryId == 0 and $userFilter->cityId == 0){
                $usersFrom = Yii::t('app','World');
            } else {
                if($userFilter->countryId>0){
                    $usersFrom.= \common\models\Country::getCountryName($userFilter->countryId);
                }

                if($userFilter->cityId>0){
                    $usersFrom.= '/'.\common\models\City::getCityName($userFilter->cityId);
                }
            }

            ?>  <a  id="user-filter-button1" style="cursor: pointer; color: #34474f"><?= $usersFrom;?> <img src="/images/icons/edit2.png"></a>
        </div>
        <div class="clearfix"></div>
    </div>


    <div class="row" id="user-filter" style="display: none;padding-top:10px;background-color: #FFF;border: 1px solid #e3e3e3;margin-bottom: 10px;">

        <?php
        $form = ActiveForm::begin(['id' => 'user-form-filter','action' => \yii\helpers\Url::toRoute('site/users')]);
        ?>
        <div class="col-md-4 col-lg-4  user-filter-country">
            <div class="user-filter-title">
                <?= Yii::t('app','Country').'/'.Yii::t('app','City'); ?>
            </div>
            <?php
            echo $form->field($userFilter,'countryId')->dropDownList($countries,
                ['onchange' => 'getCities(this,1);', 'prompt' => Yii::t('app','Country')])->label(false);

            echo $form->field($userFilter,'cityId')->dropDownList($cities,
                ['class' => 'dynamic-city-input form-control', 'prompt' => Yii::t('app','City')])->label(false);
            ?>
        </div>
        <div class="col-md-4 col-lg-4 user-filter-radio">
            <div class="user-filter-title">
                <?= Yii::t('app','Sex');  ?>
            </div>
            <?=  $form->field($userFilter,'sex')->radioList([
                0 => Yii::t('app', 'Men'),
                1 => Yii::t('app', 'Women'),
                2 => Yii::t('app', "Don't matter"),
            ])->label(false);?>

        </div>
        <div class="col-md-4 col-lg-4">
            <div class="user-filter-title">
                <?= Yii::t('app','Age');  ?>
            </div>
            <?php
            echo $form->field($userFilter, 'ageRange')->textInput([
                'id' =>'discoveryfilterform-agerange',
                'data-slider-min' => User::AGE_MIN,
                'data-slider-max' => User::AGE_MAX,
                'data-slider-step' => "1",
                'data-slider-value' =>$userFilter->ageRange,
            ])->label(false);
            ?>
            <?php
            echo $form->field($userFilter,'onlineStatus')->checkbox()->label(Yii::t('app','Only online'));
            ?>

            <?php

            echo $form->field($userFilter,'issetPhoto')->checkbox()->label(Yii::t('app','With profile photo'));
            ?>

        </div>
        <div class="col-md-12">
            <div class="form-group text-center">
                <?php
                echo \yii\helpers\Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'signup-button','id' => 'user-filter-submit'])." ";
                echo \yii\helpers\Html::button(Yii::t('app', 'Close'), ['class' => 'btn btn-default', 'name' => 'Close','id' => 'user-filter-button'])." ";
                ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>


    <?= $this->render('partials/users_gallery.php', ['users' => $users]); ?>


    <?php
    if ($pages) {
        // display pagination
        echo '<div class="text-center">';
        echo \yii\widgets\LinkPager::widget([
            'pagination' => $pages,
            'options' => [
                'class' => 'pagination',
                'style' => 'display:inline-block'
            ],
            'maxButtonCount' => 6
        ]);
        echo '</div>';
    }
    ?>
    <div class="row" id="user-filter1" style="margin-top:10px;padding-top: 13px;color:#37474f;font: 18px Helvetica,Arial,sans-serif;margin-bottom: 10px;border: 1px solid #e3e3e3;height: 50px;background-color: #FFF !important;">
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
            <a href="<?= Url::to(['/profile/vip-users/']);?>" class="link-default">
                <?= Yii::t('app', 'VIP Users')?>
            </a>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6" style="text-align: right">
            <a  id="vip-user-button" data-toggle="modal" data-target="#vip-user-modal" style="cursor: pointer; color: #34474f">
                <?= Yii::t('app', 'Become a vip user') ?>  <i class="glyphicon glyphicon-ok" style="font-size: 14px;"></i>
            </a>
        </div>
        <div class="clearfix"></div>
    </div>


    <?= $this->render('partials/test_users_gallery.php', ['users' => $vipUsers]); ?>

</div>

