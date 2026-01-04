<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 18.04.2015
 * Time: 16:28
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use common\models\User;

?>

<!-- Modal -->
<div class="modal fade" id="dating-filter-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"><?= Yii::t('app', 'Filter') ?></h4>

            </div>

            <div class="modal-body">

                <?php $form1 = ActiveForm::begin(['id' => 'form-filter']); ?>

                <?= $form1->field($discoveryFilterForm, 'countryId')->dropDownList($countries,
                    ['onchange' => 'getCities(this,1);', 'prompt' => '---']) ?>

                <?= $form1->field($discoveryFilterForm, 'cityId')->dropDownList($cities,

                    ['class' => 'dynamic-city-input form-control', 'prompt' => '---']) ?>

                <?= $form1->field($discoveryFilterForm, 'ageRange')->textInput([
                    'data-slider-min' => User::AGE_MIN,
                    'data-slider-max' => User::AGE_MAX,
                    'data-slider-step' => "1",
                    'data-slider-value' =>$discoveryFilterForm->ageRange,
                ])->label(Yii::t('app', 'Age')) ?>

                <?= $form1->field($discoveryFilterForm, 'sex')->radioList([
                    0 => Yii::t('app', 'Men'),
                    1 => Yii::t('app', 'Women'),
                    2 => Yii::t('app', "Don't matter"),
                ])->label(false) ?>

                <div class="form-group text-center">
                    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
                <?php ActiveForm::end(); ?>

            </div>  <!--Modal body-->

            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?= Yii::t('app', 'Close') ?></button>
            </div>
        </div>
    </div>
</div><!-- Modal -->