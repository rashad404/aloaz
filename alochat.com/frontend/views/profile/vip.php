<?php
/* @var $this yii\web\View */
$this->title = Yii::t('app', 'VIP Users');
?>
<?= $this->render('/site/partials/set_vip_user_modal'); ?>





<div class="profile-page-container profile-page-main">

    <div class="row title-block" id="user-filter1">
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6">
            <div style="border-radius: 50%;float: left ;width: 24px;height: 24px;background: #e3bb1a;margin-right: 5px;margin-top: -2px">
                <img src="/images/icons/rates.png" style="margin-top:4px; margin-left:4px;  " height="14" width="16">
            </div>
            <div class="pull-left">
                <?= $this->title ?>

            </div>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6" style="text-align: right">
            <a  id="vip-user-button" data-toggle="modal" data-target="#vip-user-modal" style="cursor: pointer; color: #34474f">
                <?= Yii::t('app', 'Become a vip user') ?>  <i class="glyphicon glyphicon-ok" style="font-size: 14px;"></i>
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
    <?= $this->render('partials/users_gallery.php', ['users' => $users]); ?>


    <?php
    if ($pages) {
        // display pagination
        echo '<div class="text-center">';

        echo \yii\widgets\LinkPager::widget([
            'pagination' => $pages,
        ]);
        echo '</div>';
    }
    ?>


</div>