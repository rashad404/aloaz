<?php
/* @var $this yii\web\View */

use frontend\assets\PhotoUploadAsset;
PhotoUploadAsset::register($this);

$this->title =Yii::t('app', 'Friends');
?>
<div class="profile-page-container profile-page-main">


    <div class="row title-block" id="user-filter1">
        <div class="col-md-12">

            <div style="border-radius: 50%; width: 24px;height: 24px;background: #E3BB1A ;margin-right: 5px;float: left;margin-top: -1px">
                <img src="/images/icons/friend-icon.png" width="16" height="16" style="margin-top: 3px; margin-left:4px;">
            </div>
            <div class="pull-left"> <?= $this->title ?> </div>
        </div>
    </div>
    <?= $this->render('partials/friend_users_gallery.php', ['users' => $users]); ?>


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