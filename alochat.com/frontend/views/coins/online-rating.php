<?php
use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
$this->title = Yii::t('app','Increase your online position');
?>



<div class="profile-page-container profile-page-main" style="padding-top: 0px !important;background-color: transparent;border: none;">

    <div class="row title-block" id="user-filter1">
        <div class="col-md-12">
            <div class="pull-left">  <?= $this->title;  ?></div>

        </div>
    </div>
    <div class="row" style="background-color: #FFF;padding: 20px;">
        <?= Yii::t('app','Coins in your acount')?> :  <?= Yii::$app->user->identity->coins?><br />
        Xallarızın sayı nə qədər çox olarsa bir o qədər Onlaynda irəlidə görünəcəksiniz. <br />
        Xallarınızın sayı: <?= Yii::$app->user->identity->point; ?>. Loqininizin onlayndakı mövqeyi: <?= $place?> <br />
        Xallarınızın sayını artırmaqla daha da irəli sırada görünə bilərsiniz. Bu da sizin loqinin daha çox görünməsinə və gələn mesajların artmasına səbəb olacaq, yeni dostlar qazanacaqsınız. <br />
        <div class="settings-content">
            <?php $form2 = ActiveForm::begin(['id' => 'form-nick-change']); ?>

            <div class="col-md-8 col-lg-8 col-xs-8 col-sm-8">
                <?= $form2->field($form, 'point')->dropDownList($form->getPoints()) ?>
            </div>
            <div class="col-md-4 col-lg-4 col-xs-4 col-sm-4" style="margin-top: 25px;">
                <?= Html::submitButton(Yii::t('app', 'Təsdiqlə'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>

            </div>
            <div class="clearfix"></div>
            <?php ActiveForm::end(); ?>
        </div>
        <p>

         <b>Qeyd</b> Onlaynda loqinlerin yanında yazılan "+" işarəsi həmin istifadəçinin xallarının sayını göstərir. Xallar gecə saat 12 tamamda sıfırlanır.
        </p>

    </div>



</div>
