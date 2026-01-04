<?php
/**
 * Created by PhpStorm.
 * User: Elvin
 * Date: 18.04.2015
 * Time: 16:28
 */
use yii\helpers\Url;
use \yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;

?>

<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-hidden="true">

<div class="modal-dialog">

    <div class="modal-content">

        <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" ><?= Yii::t('app', 'Terms of use') ?></h4>

        </div> <!-- Modal header -->
        <div class="modal-body">
            sss
        </div>  <!-- Modal body -->

        <div class="modal-footer">

            <button type="button" class="btn btn-default" data-dismiss="modal">
                <?= Yii::t('app', 'Close') ?>
            </button>
        </div>  <!-- Modal footer -->

    </div> <!-- Content -->

</div> <!-- Dialog -->

</div><!-- Modal -->

<div class="modal fade" id="privacyModal" tabindex="-2" role="dialog" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" ><?= Yii::t('app', 'Privacy Policy') ?></h4>

            </div> <!-- Modal header -->
            <div class="modal-body">
            </div>  <!-- Modal body -->

            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?= Yii::t('app', 'Close') ?>
                </button>
            </div>  <!-- Modal footer -->

        </div> <!-- Content -->

    </div> <!-- Dialog -->

</div><!-- Modal -->

<div class="main">

    <div class="logo"></div>

    <div class="signup-block">

        <?= \frontend\widgets\Alert::widget() ?>

        <?php $form = ActiveForm::begin(['id' => 'form-signup','enableClientValidation' => false,
            'enableAjaxValidation' => false]); ?>
        <input type="text" name="nickname" style="display: none">
        <input type="password" name="password" style="display: none">

<!--        <input type="text" name="nickname" autocomplete="off">
-->
        <?= $form->field($signupForm, 'nickname')
            ->textInput(['placeholder' => Yii::t('app', 'Nick'), 'value' => '','autocomplete'=>"off"])->label(false) ?>

        <?= $form->field($signupForm, 'password')
            ->passwordInput(['placeholder' => Yii::t('app', 'Password')])
            ->label(false)
        ?>

        <?= $form->field($signupForm, 'full_name')
            ->textInput(['placeholder' => Yii::t('app', 'Name')])->label(false) ?>

        <?= $form->field($signupForm, 'email')
            ->textInput(['placeholder' => Yii::t('app', 'Email')])->label(false) ?>


        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?= $form->field($signupForm, 'sex')->dropDownList([
                    User::SEX_MAN => Yii::t('app', 'Man'),
                    User::SEX_WOMAN => Yii::t('app', 'Woman')
                ],
                    ['prompt' => Yii::t('app', 'Sex')]
                )->label(false) ?>
            </div>

           <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?/*= $form->field($signupForm, 'age')->dropDownList(
                    User::getAgeArray(),
                    ['prompt' => Yii::t('app', 'Age')]
                )->label(false) */?>
            </div>-->

        </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="padding-right: 0px;">
                <?= $form->field($signupForm, 'b_day')->dropDownList(
                    User::getDays()
                 )->label(false) ?>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="padding-right: 0px;padding-left: 0px;">
                <?= $form->field($signupForm, 'b_month')->dropDownList(
                    User::getMonths()
                 )->label(false) ?>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="padding-left: 0px;">
                <?= $form->field($signupForm, 'b_year')->dropDownList(
                    User::getYears()

                 )->label(false) ?>
            </div>
        </div>



       <!-- <div class="form-group">
            <div class="g-recaptcha" data-sitekey="6LdSSAcTAAAAAB327JM4W97fd2C328gPyM_W0NaZ"></div>
        </div>-->

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Create account'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <p class="or-signin"><?= Yii::t('app', 'or sign in with') ?></p>

        <?= yii\authclient\widgets\AuthChoice::widget([
            'baseAuthUrl' => ['site/auth'],
            'popupMode' => false,
        ]) ?>

        <p class="policy-warning">

            <?=Yii::t('app',"By authorizing you agree to our {policy} and to receive newsletters.",
                [
                    'policy' => "<a   data-toggle='modal' data-target='#privacy-modal'>".Yii::t('app','Terms of use').", ".Yii::t('app','Privacy Policy')."</a>"
                ]
            )?>
        </p>

    </div>
</div>
