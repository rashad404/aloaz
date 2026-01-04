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
use \yii\helpers\Url;

?>

<!-- Modal -->
<div class="modal fade" id="vip-user-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"><?= Yii::t('app', 'Vip User') ?></h4>

            </div>

            <div class="modal-body">
                <?= Yii::t('app','{coin} coins will be deducted from your account to become a VIP user.',['coin' => Yii::$app->params["minCoinsForVipUser"]])?>

            </div>  <!--Modal body-->

            <div class="modal-footer">
                <a href="<?php echo Url::to(['/profile/set-vip'])?>" class="btn btn-success"><?= Yii::t('app','Become a vip user')?></a>

                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?= Yii::t('app', 'Close') ?></button>
            </div>
        </div>
    </div>
</div><!-- Modal -->