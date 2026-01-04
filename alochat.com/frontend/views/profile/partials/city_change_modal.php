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
<!-- Modal -->
<div class="modal fade" id="citySelectModal" tabindex="-1" role="dialog" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"><?= Yii::t('app', 'Select your city') ?></h4>

            </div> <!-- Modal header -->

            <div class="modal-body">

                <?php $form1 = ActiveForm::begin(['id' => 'form-city']); ?>

                    <?= $form1->field($citySelectForm, 'countryId')->dropDownList($countries,
                        ['onchange' => 'getCities(this,0);']) ?>

                    <?= $form1->field($citySelectForm, 'cityId')->dropDownList($cities,
                        ['class' => 'dynamic-city-input form-control']) ?>

                    <div class="form-group text-center">
                        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?> <!-- Form -->

            </div>  <!-- Modal body -->

            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?= Yii::t('app', 'Close') ?>
                </button>

            </div>  <!-- Modal footer -->

        </div> <!-- Content -->

    </div> <!-- Dialog -->

</div><!-- Modal -->