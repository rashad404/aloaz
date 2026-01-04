<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \common\models\User;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

$this->title = Yii::t('app', 'Signup');
$this->params['breadcrumbs'][] = $this->title;
\frontend\assets\SignupAsset::register($this);

$lang = Yii::$app->language;

$script = <<<JS
var RecaptchaOptions = {
    lang : '$lang'
};
JS;

$this->registerJs($script,$this::POS_HEAD);

?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('app', 'Please fill out the following fields to signup:'); ?></p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <?= $form->field($model, 'full_name') ?>
            <?= $form->field($model, 'email') ?>
            <?= $form->field($model, 'sex')->dropDownList([
                User::SEX_MAN => Yii::t('app', 'Man'),
                User::SEX_WOMAN => Yii::t('app', 'Woman')
            ],
                ['prompt' => '---']
            ) ?>

            <?= $form->field($model, 'age')->dropDownList(
                User::getAgeArray(),
                ['prompt' => '---']
            ) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>
            <div class="form-group">
                <div class="g-recaptcha" data-sitekey="6LeEiwUTAAAAAKkGs8V3QC6fOC5yAP9UvmWvliDf"></div>
            </div>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Signup'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
