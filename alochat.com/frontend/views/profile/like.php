<?php
/* @var $this yii\web\View */
$this->title =Yii::t('app', 'Likes me');
?>
<div class="profile-page-container profile-page-main">


    <div class="row title-block" id="user-filter1">
        <div class="col-md-12">

            <div style="border-radius: 50%; width: 24px;height: 24px;background: #e3842a;margin-right: 5px;float: left;margin-top: -1px">
                <img src="/images/icons/like.png" width="12" height="14" style="margin-top:4px; margin-left:6px;">
            </div>
            <div class="pull-left"> <?= $this->title ?> </div>
        </div>
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