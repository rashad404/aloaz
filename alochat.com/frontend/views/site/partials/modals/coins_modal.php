<?php
use yii\helpers\Html;
use \yii\helpers\Url;
?>

<!-- Modal -->
<div class="modal fade" id="coins-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"><?= Yii::t('app', 'Coins') ?></h4>

            </div>

            <div class="modal-body">

            <?= Yii::t('about','By special services in the use you as Coin.You get free Coins. As you must be active in order to increase the number of Coins. As you earn Coins for every 30 minutes in which you automatically.')?>

            </div>  <!--Modal body-->

            <div class="modal-footer">
                <a href="<?php echo Url::to(['/profile/set-vip'])?>" class="btn btn-success"><?= Yii::t('app','Become a vip user')?></a>

                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <?= Yii::t('app', 'Close') ?></button>
            </div>
        </div>
    </div>
</div><!-- Modal -->