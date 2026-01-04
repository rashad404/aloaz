<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\models\User;
use frontend\assets\DiscoveryAsset;

/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Users');
DiscoveryAsset::register($this);
?>
<?php /*  echo $this->render('/site/partials/set_vip_user_modal'); */ ?>


<div class="profile-page-container">
    <?php $form1 = ActiveForm::begin(['id' => 'form-filter']); ?>

    <?= $form1->field($discoveryFilterForm, 'countryId')->dropDownList($countries,
        ['onchange' => 'getCities(this,1);', 'prompt' => '---']) ?>

    <?= $form1->field($discoveryFilterForm, 'cityId')->dropDownList($cities,

        ['class' => 'dynamic-city-input form-control', 'prompt' => '---']) ?>

    <?= $form1->field($discoveryFilterForm, 'ageRange')->textInput([
        'data-slider-min' => User::AGE_MIN,
        'data-slider-max' => User::AGE_MAX,
        'data-slider-step' => "1",
        'data-slider-value' =>[18,40],
    ])->label(Yii::t('app', 'Age')) ?>

    <?= $form1->field($discoveryFilterForm, 'sex')->radioList([
        0 => Yii::t('app', 'Men'),
        1 => Yii::t('app', 'Women'),
        2 => Yii::t('app', "Don't matter"),
    ])->label(false) ?>

    <div class="form-group text-center">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>



    <!--<h1><?/*= $this->title */?> </h1>
            <?php /*$form = ActiveForm::begin(['id' => 'form-filter'])*/?>


                <?/*= $form->field($userFilterForm,'countryId')->dropDownList($countries,
                ['onchange' => 'getCities(this,1);', 'prompt' => '---']); */?>


                 <?/*= $form->field($userFilterForm, 'cityId')->dropDownList($cities,
                ['class' => 'dynamic-city-input form-control', 'prompt' => '---']) */?>

                <?/*= $form->field($userFilterForm, 'ageRange')->textInput([
                    'data-slider-min' => User::AGE_MIN,
                    'data-slider-max' => User::AGE_MAX,
                    'data-slider-step' => "1",
                    'data-slider-value' =>$userFilterForm->ageRange,
                ])->label(Yii::t('app', 'Age')) */?>

                <?/*= $form->field($userFilterForm, 'sex')->radioList([
                    0 => Yii::t('app', 'Men'),
                    1 => Yii::t('app', 'Women'),
                    2 => Yii::t('app', "Don't matter"),
                ])->label(false) */?>

            <?/*= $form->field($userFilterForm, 'online')->checkbox(array('label'=>'Online')); */?>
            <?/*= $form->field($userFilterForm, 'profile_photo')->checkbox(array('label'=>'Profil Foto')); */?>
            --><?php /*ActiveForm::end();*/?>

    <?= $this->render('partials/users_gallery.php', ['users' => $users1]); ?>


    <?php
    if ($pages) {
        // display pagination
        echo \yii\widgets\LinkPager::widget([
            'pagination' => $pages,
        ]);
    }
    ?>

    <div class="panel panel-default row" style="margin-left: 2px;margin-top: 30px;margin-bottom: 0px">
        <div class="panel-heading">
            <h3 class="panel-title"><div class="discovery-vip-title" style="height: 60px !important; ">
                    <div class="inline-block">

                        <div class="vip-ic"></div>
                        <div class="pull-left"> <a href="<?= Url::to(['/profile/vip-users/']);?>" class="link-default"><?= Yii::t('app','VIP USERS')?></a></div>

                        <div class="vip-ic"></div>

                        <div class="clearfix"></div>
                        <button id="vip-user-button" data-toggle="modal" data-target="#vip-user-modal"
                                title="<?= Yii::t('app', 'Become a vip user') ?>" class="btn btn-sm btn-default pull-center">
                            <i class="glyphicon glyphicon-ok"></i>
                            <?= Yii::t('app', 'Become a VIP user'); ?>
                        </button>
                    </div>
                </div></h3>
        </div>
        <div class="panel-body">
            <?= $this->render('partials/test_users_gallery.php', ['users' => $vipUsers]); ?>        </div>
    </div>


</div>