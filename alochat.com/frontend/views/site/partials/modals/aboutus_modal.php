<?php
use yii\helpers\Html;
use \yii\helpers\Url;
?>

<!-- Modal -->
<div class="modal fade" id="aboutus-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"><?= Yii::t('about', 'About Us') ?></h4>

            </div>

            <div class="modal-body">
                <p>
                    <?= Yii::t('about','AloChat is a great platform to meet new people in your area and around the world.')?>
                </p>
                <p>
                    <?= Yii::t('about','about_us_text')?>
                </p>


            </div>  <!--Modal body-->

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?= Yii::t('app', 'Close') ?></button>
            </div>
        </div>
    </div>
</div><!-- Modal -->