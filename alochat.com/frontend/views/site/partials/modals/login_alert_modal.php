 <!-- Modal -->
<div class="modal fade" id="login_alert_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app','Warning')?></h4>
            </div>
            <div class="modal-body">
                <?php echo Yii::t('app','Log in to the site or register')?>
             </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app','Close')?></button>
             </div>
        </div>
    </div>
</div>