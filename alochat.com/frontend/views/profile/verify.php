<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\User;
use frontend\assets\ProfileAsset;

$this->title = Yii::t('app', 'Nömrə təsdiqi');

ProfileAsset::register($this);
?>

<div class="profile-page-container settings-page-container">

    <div class="site-login" style="padding-left: 15px; padding-right: 15px;">
         <div class="row">
             <div class="settings-header">
                 <h4><?= $this->title ?></h4>
             </div>
            <div class="col-lg-11 col-lg-offset-1">

                <?php $form = ActiveForm::begin([
                    'id' => 'verify-form',
                    'options' => ['class' => 'form-horizontal'],
                    'fieldConfig' => [
                        /*                    'template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<br /><div class=\"col-lg-2\">{error}</div>",*/
                        'labelOptions' => ['class' => 'col-lg-2 control-label'],
                    ],
                ]); ?>
                <?php
                $input = '';
                $submitValue = '';
                $explanation = '';
                $note = '';
                if($step == 1){
                    $explanation = 'Profili təsdiqləmək üçün mobil nömrənizi daxil edin ';
                    $note = '<b>Qeyd:</b><br /><i> Daxil etdiyiniz nömrəyə təsdiq kodu göndəriləcək.<br />
Eyni nömre ile yalnız 1 defe qeyd olmaq mümkündür.<br />
Qeydiyyatdan keçmək tam pulsuzdur.<br />
Nömrənizin anonimliyi tam qorunacaq. <br />Nömrənizdən yalnız parol bərpasında istifade oluna biler.</i> <br />';
                    $input =  $form->field($model,'phone',[
                        'template' => '{label}  <div class="col-lg-5">{input}{error}{hint}</div>',
                        'inputTemplate' => '<div class="input-group"><span class="input-group-addon">+994</span>{input}</div>'
                    ])->textInput()->label(false);
                    $submitValue = 'Davam et';
                } elseif($step == 2) {
                    $explanation = 'Mobil nömrənizə göndərilmiş kodu daxil edin ';
                    $input =  $form->field($model,'code',[
                        'template' => '{label}  <div class="col-lg-5">{input}{error}{hint}</div>',
                        'inputTemplate' => '<div class="input-group"><span class="input-group-addon">Kod</span>{input}</div>',
                    ])->textInput()->label(false);
                    $submitValue = 'Təsdiqlə';
                }
                echo "<span style='font-size: 17px;'>".$explanation." </span><br />";

                echo $input;
                ?>
                <div class="form-group pull-left">
                    <div class="col-lg-offset-6 col-lg-1">
                        <?= Html::submitButton(Yii::t('app',$submitValue), ['class' => 'btn btn-primary', 'name' => 'verify-button']) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
             <div class="clearfix"></div>
             <div class="col-lg-11 col-lg-offset-1" style="margin-bottom: 20px;"><?= $note;?></div>

        </div>
    </div>
</div>





