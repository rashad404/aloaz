<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;
use frontend\assets\ProfileAsset;

$this->title = Yii::t('app', 'Profile Settings');

//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profile'), 'url' => ['/profile/index', 'id' => Yii::$app->user->id]];
//$this->params['breadcrumbs'][] = Yii::t('app', 'Settings');
ProfileAsset::register($this);
?>

<div class="profile-page-container settings-page-container">

    <div class="settings-header">
        <h4><?= Yii::t('app', 'Change phone') ?></h4>
    </div>
    <div class="settings-content">
        <?php $form= ActiveForm::begin(['id' => 'form-password', 'action' => '/profile/update-password']); ?>

         <div class="form-group field-phonechangeform-phone required">
            <label class="control-label" for="phonechangeform-phone">Phone</label>
            <input id="phonechangeform-phone" class="form-control" name="PhoneChangeForm[phone]" type="text">

            <div class="help-block"><?= $phoneChangeForm->getErrors()[0];?></div>
        </div>
          <hr/>
        <div class="form-group pull-right">
            <?= Html::submitButton(Yii::t('app', 'Change'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>





