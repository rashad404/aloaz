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
<div class="modal fade" id="giftModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app', 'Send gift') ?></h4>
            </div>
            <div class="modal-body">

                <div id="modal_id">
                    Yuklenir ...
                </div>
             </div>
        </div>
    </div>
</div>
