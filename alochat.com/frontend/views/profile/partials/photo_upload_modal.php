<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 27.04.2015
 * Time: 10:06
 */

use yii\widgets\ActiveForm;
?>

<!-- Modal -->
<div class="modal fade" id="photoUploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app', 'Add yout photos') ?></h4>
            </div>
            <div class="modal-body">

                <?php $form = ActiveForm::begin([
                    'id' => 'image-upload-form',
                    'options' => ['enctype' => 'multipart/form-data']]); ?>

                <div class="form-group">

                    <?= $form->field($imageForm, 'image[]')->fileInput(['multiple' => true, 'id' => "file-3"]) ?>

                    <!--                        <input id="file-3" type="file" multiple=true>-->
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function() {
        $(function () {
     });
</script>