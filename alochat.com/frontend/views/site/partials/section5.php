<?php
/**
 * Created by PhpStorm.
 * User: Elvin
 * Date: 18.04.2015
 * Time: 16:28
 */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="line1">
    <div class="login-block">

        <p class="or-signin"><?= Yii::t('app', 'Login') ?></p>

        <?php $form = ActiveForm::begin(['id' => 'form-sigin']); ?>

        <?= $form->field($loginForm, 'email')
            ->textInput(['placeholder' => Yii::t('app', 'Email')])->label(false) ?>

        <?= $form->field($loginForm, 'password')
            ->passwordInput(['placeholder' => Yii::t('app', 'Password')])
            ->label(false)
        ?>
        <!--        <div style="color:#999;margin:1em 0">-->
        <!--            --><?php //=Yii::t('app','Forgot your password ?');?>
        <!--            --><?php //= Html::a(Yii::t('app','reset it'), ['site/request-password-reset']) ?><!--.-->
        <!--        </div>-->
        <div class="form-group">
            <span style="float: left"><a href="<?= Url::to(['site/request-password-reset'])?>"   style="color:#CCC;padding: 5px;float: left"><?= Yii::t('app','Forgot your password?');?></a></span>
            <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-primary pull-right', 'name' => 'signup-button']) ?>
            <div class="clearfix"></div>
        </div>

        <?php ActiveForm::end(); ?>
        <div class="clearfix"></div>
        <p class="or-signin"><?= Yii::t('app', 'or sign in with') ?></p>

        <?= yii\authclient\widgets\AuthChoice::widget([
            'baseAuthUrl' => ['site/auth'],
            'popupMode' => false,
        ]) ?>

    </div>

</div>

<footer class="footer">

    <p>&copy; Alochat.com <?= date('Y') ?>.<?= Yii::t('app', 'All rights reserved') ?></p>

</footer>
