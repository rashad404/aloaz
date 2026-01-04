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
$this->title = Yii::t('app', 'Search');
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

<div class="profile-page-container profile-page-main" style="padding-top: 0px !important;background-color: transparent;border: none;">

    <div class="row" style="color:#37474f;padding-top: 14px;font: 18px Roboto,Helvetica,Arial,sans-serif;margin-bottom: 10px;border: 1px solid #e3e3e3;height: 50px;background-color: #FFF !important;">
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
            <?= Yii::t('app','Search'); ?>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6" style="text-align: right">
               <a  id="user-search-button" style="cursor: pointer; color: #34474f;font-size: 13px;">Axtarışı dəyiş <img src="/images/icons/edit2.png"></a>
        </div>
        <div class="clearfix"></div>
    </div>


    <?php
    $user_filter_style = '';
    if($issetPost and count($users)>0){
        $user_filter_style = "display:none;";
    }
    ?>

    <div class="row user-filter" style="<?= $user_filter_style;?>padding-top: 14px;margin-bottom: 10px;border: 1px solid #e3e3e3;background-color: #FFF !important;">
        <?php
        $formLogin = ActiveForm::begin(['id' => 'user-form-filter','enableClientValidation' => false,'method' => 'get',
            'enableAjaxValidation' => false,'action' => \yii\helpers\Url::toRoute('site/search')]);
        ?>

        <div class="row user-login-filter" style="margin-left: 0px; margin-right: 0px;padding-left: 25px;">
            <div class="col-md-4 col-lg-4 col-sm-4 col-xs-12">
                <?php
                echo $formLogin->field($searchUser,'login')->textInput();
                ?>
            </div>
            <div class="col-md-3 col-lg-3 col-sm-4 col-xs-12 search-similarity">

                <?=  $formLogin->field($searchUser,'similarity')->dropDownList([ 0 => 'Deqiq', 1 => 'Oxşar'],
                    ['class' => 'form-control search-similarity'])->label(false)?>
            </div>
            <div class="col-md-3 col-lg-3 col-sm-4 col-xs-12 user-login-search-btn">

                <?=
                \yii\helpers\Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary','style' => 'margin-bottom: 15px; ', 'name' => 'signup-button','id' => 'user-filter-submit'])." ";
                ?>
            </div>

        </div>

        <?php ActiveForm::end();?>
    </div>


        <div class="row user-filter" style="<?= $user_filter_style?>padding-top:10px;background-color: #FFF;border: 1px solid #e3e3e3;margin-bottom: 10px;">

        <?php
        $form = ActiveForm::begin(['id' => 'user-form-filter','enableClientValidation' => false,'method' => 'get',
            'enableAjaxValidation' => false,'action' => \yii\helpers\Url::toRoute('site/search')]);
        ?>
        <div class="col-md-4 col-lg-4  user-filter-country">
            <div class="user-filter-title">
                <?= Yii::t('app','Country').'/'.Yii::t('app','City'); ?>
            </div>
            <?php
            echo $form->field($searchUser,'countryId')->dropDownList($countries,
                ['onchange' => 'getCities(this,1);', 'prompt' => Yii::t('app','Country')])->label(false);

            echo $form->field($searchUser,'cityId')->dropDownList($cities,
                ['class' => 'dynamic-city-input form-control', 'prompt' => Yii::t('app','City')])->label(false);
            ?>
        </div>
        <div class="col-md-4 col-lg-4 user-filter-radio">
            <div class="user-filter-title">
                <?= Yii::t('app','Sex');  ?>
            </div>
            <?=  $form->field($searchUser,'sex')->radioList([
                0 => Yii::t('app', 'Men'),
                1 => Yii::t('app', 'Women'),
                2 => Yii::t('app', "Don't matter"),
            ])->label(false);?>

        </div>
        <div class="col-md-4 col-lg-4 user-filter-age">
            <div class="user-filter-title">
                <?= Yii::t('app','Age');  ?>
            </div>
            <?php
            echo $form->field($searchUser, 'ageRange')->textInput([
                'id' =>'discoveryfilterform-agerange',
                'data-slider-min' => User::AGE_MIN,
                'data-slider-max' => User::AGE_MAX,
                'data-slider-step' => "1",
                'data-slider-value' =>$searchUser->ageRange,
            ])->label(false);
            ?>
            <?php
            echo $form->field($searchUser,'onlineStatus')->checkbox()->label(Yii::t('app','Only online'));
            ?>

            <?php

            echo $form->field($searchUser,'issetPhoto')->checkbox()->label(Yii::t('app','With profile photo'));
            ?>

        </div>
        <div class="col-md-4 col-md-offset-7" style="padding-left: 25px;">
            <div class="form-group">
                <?php
                echo \yii\helpers\Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary','style' => 'margin-top: 10px; ', 'name' => 'signup-button','id' => 'user-filter-submit'])." ";
                 ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>


    <?= $this->render('partials/search_users_gallery.php', ['users' => $users,'action' => $action]); ?>


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




</div>

<div style="display: none">
    <?= 'Opened: '.$timeFerq?>
</div>