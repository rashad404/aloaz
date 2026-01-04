<?php
/* @var $this yii\web\View */
$this->title =Yii::t('app', 'Mutual likes');
?>

<div class="profile-page-container profile-page-main">


    <div class="row title-block" id="user-filter1">
        <div class="col-md-12">
            <div style="border-radius: 50%; width: 24px;height: 24px;background: #24a2f1;margin-right: 5px;float: left;margin-top: -1px">
                <img src="/images/icons/mutual-likes.png" width="16" height="15" style="margin-top:4px; margin-left:3px;  ">
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