<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Compotetion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="compotetion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?//= $form->field($model, 'start_date')->textInput() ?>
    <label>Konkursun başlama vaxtı</label>
    <?= \dosamigos\datepicker\DatePicker::widget([
        'model' => $model,
        'attribute' => 'start_date',
        'template' => '{addon}{input}',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'dd-M-yyyy'
        ]
    ]);?><br />
    <label>Konkursun bitmə vaxtı</label>


    <?//= $form->field($model, 'end_date')->textInput() ?>


    <?= \dosamigos\datepicker\DatePicker::widget([
        'model' => $model,
        'attribute' => 'end_date',
        'template' => '{addon}{input}',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'dd-M-yyyy'
        ]
    ]);?>
    <br />
    <?//= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList([0 => 'Passiv', 1 => 'Aktiv'])?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
