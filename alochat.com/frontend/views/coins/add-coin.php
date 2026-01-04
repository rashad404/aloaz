<?php
use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
$this->title = Yii::t('app', 'Add Coin');
?>



<div class="profile-page-container profile-page-main" style="padding-top: 0px !important;background-color: transparent;border: none;">

    <div class="row title-block" id="user-filter1">
        <div class="col-md-12">
            <div class="pull-left">  SMS ilə bal almaq</div>

        </div>
    </div>
    <div class="row" style="background-color: #FFF;padding: 20px;border: 1px solid #e3e3e3;margin-bottom: 15px;">
          <div class="settings-content">
             <div >
                Qisa nömrələr ve tarifləri:<br />
                 9136 – 0,50 azn - 20 bal <br />
                 9142 – 1,00 azn - 50 bal<br />
                 9143 - 2,00 azn - 120 bal<br />
                 9148 – 5,00 azn - 300 bal<br />
            </div>

        </div>
        <p>

         </p>
    </div>
    <div class="row title-block" id="user-filter1">
        <div class="col-md-12">
            <div class="pull-left"> Portmanat ilə bal almaq</div>

        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6" style="padding-left: 0px;">
                    <div style="border:1px solid #e3e3e3;background-color: #FFF;text-align: center;padding: 10px;">
                        <form action='<?= \yii\helpers\Url::to(["/coins/payment-redirect"])?>' method='post'>
                            <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />                            <input type='hidden' name='s_id' value='11866'>
                            <input type='hidden' name='o_id' value='1129446'>
                            <input type='hidden' name='method' value='account'>
                            <input type='text' name='amount' value='1'>
                            <input type='submit' value='Portmanat Hesabla ödə' class="hidden">
                            <input src="/images/icons/portmanat.png" value="Portmanat Kodla ödə" style="padding-top: 10px" type="image">
                        </form>
                    </div>
                </div>
                <div class="col-md-6" style="padding-right: 0px;">
                    <div style="border:1px solid #e3e3e3;background-color: #FFF;text-align: center;padding: 10px;">
                        <form action='<?= \yii\helpers\Url::to(["/coins/payment-redirect"])?>' method='post'>
                            <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                             <input type='hidden' name='method' value='code'>
                            <input src="/images/icons/portmanat_code.png" value="Portmanat Kodla ödə" style="padding-top: 10px" type="image">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>
