<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Gift */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gift-form">

    <?php $form = ActiveForm::begin([ 'options' => ['enctype'=>'multipart/form-data']]); ?>


    <?= $form->field($model, 'category_id')->dropDownList(\common\models\GiftCategory::getGiftCategories())?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'icon')->fileInput(); ?>

    <?= $form->field($model, 'coin')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList(\common\models\GiftCategory::getStatusArray()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
