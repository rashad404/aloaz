<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 18.04.2015
 * Time: 16:28
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>
<div class="settings-header">
    <h4><?= Yii::t('app', 'Change password') ?></h4>
</div>
<div class="settings-content">
    <?php $form2 = ActiveForm::begin(['id' => 'form-password', 'action' => '/profile/update-password']); ?>

    <?= $form2->field($passwordChangeForm, 'oldPassword')->passwordInput() ?>

    <hr>
    <?= $form2->field($passwordChangeForm, 'password')->passwordInput() ?>
    <hr/>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Change'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>