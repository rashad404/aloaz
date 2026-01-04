<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php
    $this->title = Yii::t('app', 'Send Share');
?>
<div class="profile-page-container">
    <h4><?= $this->title ?></h4>
    <?php $form = ActiveForm::begin(['id' => 'form-settings','options' => ['enctype' => 'multipart/form-data']]); ?>
    <?php echo $form->field($model,'text')->textarea(); ?>
    <?php echo $form->field($model,'attach')->fileInput()?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'share-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>