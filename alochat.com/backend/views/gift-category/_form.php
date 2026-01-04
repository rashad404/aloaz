<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\GiftCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gift-category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name_az')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'name_ru')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'name_en')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'name_tr')->textInput(['maxlength' => 255]) ?>

     <?= $form->field($model, 'status')->dropDownList(\common\models\GiftCategory::getStatusArray());?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
